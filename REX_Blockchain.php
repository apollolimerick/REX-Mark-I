<?php
// ============================================================
// REX_Blockchain.php
// Privát blockchain ledger a REX platformhoz
// Dummy tranzakciókhoz: BUY, SELL, SHORT, VAULT_LOCK, TOKENIZE
// Beilleszthető a REX_UI_master_control.php gen_blockchain blokkjába
// ============================================================

require_once __DIR__ . '/REX_DB_handler.php';

// ============================================================
// 1. BLOCK osztály
// ============================================================
class Block {
    public int    $index;
    public string $timestamp;
    public string $previous_hash;
    public string $hash;
    public array  $transactions;
    public int    $nonce;

    public function __construct(int $index, array $transactions, string $previous_hash = '0') {
        $this->index         = $index;
        $this->timestamp     = date('Y-m-d H:i:s');
        $this->transactions  = $transactions;
        $this->previous_hash = $previous_hash;
        $this->nonce         = 0;
        $this->hash          = $this->calculateHash();
    }

    public function calculateHash(): string {
        return hash('sha256',
            $this->index .
            $this->timestamp .
            $this->previous_hash .
            json_encode($this->transactions) .
            $this->nonce
        );
    }

    // Egyszerű Proof-of-Work: hash-nek 0-val kell kezdődnie (difficulty=1)
    public function mine(int $difficulty = 1): void {
        $target = str_repeat('0', $difficulty);
        while (substr($this->hash, 0, $difficulty) !== $target) {
            $this->nonce++;
            $this->hash = $this->calculateHash();
        }
    }
}

// ============================================================
// 2. BLOCKCHAIN osztály
// ============================================================
class Blockchain {
    /** @var Block[] */
    private array $chain = [];
    private int   $difficulty;

    public function __construct(int $difficulty = 1) {
        $this->difficulty = $difficulty;
        $this->chain[]    = $this->createGenesisBlock();
    }

    private function createGenesisBlock(): Block {
        $genesis_tx = [[
            'tx_id'      => '0x' . strtoupper(substr(hash('sha256', 'REX_GENESIS_' . time()), 0, 16)),
            'type'       => 'GENESIS',
            'from'       => 'REX_SYSTEM',
            'to'         => 'REX_LEDGER',
            'asset'      => 'REX-GLOBAL',
            'amount'     => 100000000,
            'detail'     => 'Initial AMM Pool — 100,000,000 USD / 10,000,000 REX-SHARES',
            'created_at' => date('Y-m-d H:i:s'),
        ]];
        $block = new Block(0, $genesis_tx, '0000000000000000000000000000000000000000');
        $block->mine($this->difficulty);
        return $block;
    }

    public function getLatestBlock(): Block {
        return $this->chain[count($this->chain) - 1];
    }

    public function addBlock(array $transactions): Block {
        $new_block = new Block(
            count($this->chain),
            $transactions,
            $this->getLatestBlock()->hash
        );
        $new_block->mine($this->difficulty);
        $this->chain[] = $new_block;
        return $new_block;
    }

    public function isValid(): bool {
        for ($i = 1; $i < count($this->chain); $i++) {
            $current  = $this->chain[$i];
            $previous = $this->chain[$i - 1];

            if ($current->hash !== $current->calculateHash()) return false;
            if ($current->previous_hash !== $previous->hash)  return false;
        }
        return true;
    }

    /** @return Block[] */
    public function getChain(): array {
        return $this->chain;
    }
}

// ============================================================
// 3. DUMMY TRANZAKCIÓ GENERÁTOR
//    Valós users és property_public_market adatokból épít
// ============================================================
function generateDummyTransactions(mysqli $conn): array {
    global $tx_types;

    $tx_types = ['BUY', 'SELL', 'SHORT', 'VAULT_LOCK', 'TOKENIZE'];

    // Lekérjük a valós usereket és property-ket
    $users = [];
    $res = $conn->query("SELECT id, legal_name FROM users LIMIT 100");
    while ($row = $res->fetch_assoc()) $users[] = $row;

    $properties = [];
    $res = $conn->query("SELECT id, city, country, equity_target, listing_status FROM property_public_market LIMIT 100");
    while ($row = $res->fetch_assoc()) $properties[] = $row;

    // Ha nincs adat a DB-ben, fallback dummy
    if (empty($users)) {
        $users = [
            ['id' => 1, 'legal_name' => 'System Genesis Owner'],
            ['id' => 2, 'legal_name' => 'Alice Kovacs'],
            ['id' => 3, 'legal_name' => 'Bob Smith'],
        ];
    }
    if (empty($properties)) {
        $properties = [
            ['id' => 1,  'city' => 'Budapest',  'country' => 'Hungary',       'equity_target' => 15, 'listing_status' => 'active_market'],
            ['id' => 2,  'city' => 'Berlin',    'country' => 'Germany',       'equity_target' => 10, 'listing_status' => 'underwritten'],
            ['id' => 3,  'city' => 'London',    'country' => 'United Kingdom','equity_target' => 20, 'listing_status' => 'active_market'],
        ];
    }

    // 10 blokkhoz generálunk tranzakciókat (2-4 tx/blokk)
    $all_blocks_txs = [];
    for ($b = 0; $b < 10; $b++) {
        $num_tx = mt_rand(2, 4);
        $txs    = [];
        for ($t = 0; $t < $num_tx; $t++) {
            $type     = $tx_types[array_rand($tx_types)];
            $user     = $users[array_rand($users)];
            $prop     = $properties[array_rand($properties)];
            $amount   = mt_rand(100, 50000);
            $shares   = round($amount / mt_rand(500, 5000), 4);
            $ticker   = 'REX-' . strtoupper(substr(str_replace(' ', '', $prop['city']), 0, 6)) . '-' . $prop['id'];

            // Tranzakció leírás típus szerint
            switch ($type) {
                case 'BUY':
                    $detail = "Bought {$shares} shares of {$ticker} for \${$amount} USD";
                    break;
                case 'SELL':
                    $detail = "Sold {$shares} shares of {$ticker}, received \${$amount} USD";
                    break;
                case 'SHORT':
                    $detail = "Opened short position: {$shares} shares of {$ticker} (margin: \$" . round($amount * 0.5) . ")";
                    break;
                case 'VAULT_LOCK':
                    $lock_days = [7, 30, 180, 365][array_rand([7, 30, 180, 365])];
                    $detail    = "Locked {$shares} shares of {$ticker} for {$lock_days} days in REX Vault";
                    break;
                case 'TOKENIZE':
                    $detail = "Tokenized property #{$prop['id']} ({$prop['city']}, {$prop['country']}) — {$prop['equity_target']}% equity → {$ticker}";
                    break;
                default:
                    $detail = "Unknown transaction";
            }

            $txs[] = [
                'tx_id'      => '0x' . strtoupper(substr(hash('sha256', uniqid('', true)), 0, 16)),
                'type'       => $type,
                'from'       => 'USER#' . $user['id'] . ' (' . $user['legal_name'] . ')',
                'to'         => $ticker,
                'asset'      => $ticker,
                'amount'     => $amount,
                'shares'     => $shares,
                'detail'     => $detail,
                'created_at' => date('Y-m-d H:i:s', strtotime("-" . (10 - $b) . " minutes")),
            ];
        }
        $all_blocks_txs[] = $txs;
    }
    return $all_blocks_txs;
}

// ============================================================
// 4. BLOCKCHAIN FELTÖLTÉSE ÉS MENTÉSE MySQL-be
// ============================================================
function buildAndPersistBlockchain(mysqli $conn): array {
    // 4a. Létrehozzuk a blockchain_ledger táblát ha nincs
    $conn->query("
        CREATE TABLE IF NOT EXISTS blockchain_ledger (
            id              INT(11) AUTO_INCREMENT PRIMARY KEY,
            block_index     INT(11) NOT NULL,
            block_hash      VARCHAR(64) NOT NULL UNIQUE,
            previous_hash   VARCHAR(64) NOT NULL,
            block_timestamp DATETIME NOT NULL,
            nonce           INT(11) NOT NULL DEFAULT 0,
            transactions    JSON NOT NULL,
            status          ENUM('confirmed','pending') NOT NULL DEFAULT 'confirmed',
            created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // 4b. Üresítjük az előző láncot (clean regenerate)
    $conn->query("TRUNCATE TABLE blockchain_ledger");

    // 4c. Blockchain objektum létrehozása
    $blockchain = new Blockchain(difficulty: 1);

    // 4d. Dummy tranzakciók generálása
    $all_blocks = generateDummyTransactions($conn);

    // 4e. Blokkok hozzáadása és DB-be mentése
    $stmt = $conn->prepare("
        INSERT INTO blockchain_ledger
            (block_index, block_hash, previous_hash, block_timestamp, nonce, transactions, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($all_blocks as $txs) {
        $block = $blockchain->addBlock($txs);
        $tx_json = json_encode($block->transactions, JSON_UNESCAPED_UNICODE);
        $status  = 'confirmed';
        $stmt->bind_param("isssiss",
            $block->index,
            $block->hash,
            $block->previous_hash,
            $block->timestamp,
            $block->nonce,
            $tx_json,
            $status
        );
        $stmt->execute();
    }

    // 4f. Genesis blokk mentése is
 $genesis = $blockchain->getChain()[0];
$g_json  = json_encode($genesis->transactions, JSON_UNESCAPED_UNICODE);
$g_stat  = 'confirmed';

$conn->query("
    INSERT IGNORE INTO blockchain_ledger
        (block_index, block_hash, previous_hash, block_timestamp, nonce, transactions, status)
    VALUES (
        0,
        '{$genesis->hash}',
        '{$genesis->previous_hash}',
        '{$genesis->timestamp}',
        {$genesis->nonce},
        '" . $conn->real_escape_string($g_json) . "',
        'confirmed'
    )
");

    // 4g. Validálás
    $is_valid     = $blockchain->isValid();
    $block_count  = count($blockchain->getChain());
    $tx_count     = array_sum(array_map(fn($b) => count($b->transactions), $blockchain->getChain()));

    return [
        'valid'        => $is_valid,
        'block_count'  => $block_count,
        'tx_count'     => $tx_count,
        'chain'        => $blockchain->getChain(),
    ];
}

// ============================================================
// 5. LÁNC VALIDÁLÁSA (meglévő DB-ből)
// ============================================================
function validateChainFromDB(mysqli $conn): array {
    $res    = $conn->query("SELECT * FROM blockchain_ledger ORDER BY block_index ASC");
    $blocks = $res->fetch_all(MYSQLI_ASSOC);

    if (empty($blocks)) return ['valid' => false, 'reason' => 'Empty ledger'];

    for ($i = 1; $i < count($blocks); $i++) {
        $curr = $blocks[$i];
        $prev = $blocks[$i - 1];

        // Prev hash match
        if ($curr['previous_hash'] !== $prev['block_hash']) {
            return ['valid' => false, 'reason' => "Block #{$curr['block_index']}: previous_hash mismatch"];
        }

        // Recompute hash
        $recomputed = hash('sha256',
            $curr['block_index'] .
            $curr['block_timestamp'] .
            $curr['previous_hash'] .
            $curr['transactions'] .
            $curr['nonce']
        );
        if ($recomputed !== $curr['block_hash']) {
            return ['valid' => false, 'reason' => "Block #{$curr['block_index']}: hash tampered!"];
        }
    }

    return ['valid' => true, 'block_count' => count($blocks)];
}
