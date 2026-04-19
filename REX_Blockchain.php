<?php
// ============================================================
// REX_Blockchain.php
// Privát blockchain ledger a REX platformhoz
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
            'user_id'    => 0, // System
            'asset'      => 'REX-GLOBAL-IDX',
            'amount'     => 100000000,
            'shares'     => 10000000,
            'detail'     => 'Initial Global AMM Pool: $100,000,000 USD / 10,000,000 REX-SHARES',
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
// 3. DUMMY TRANZAKCIÓ GENERÁTOR (UI HIERARCHIA ALAPJÁN)
// ============================================================
function generateDummyTransactions(mysqli $conn): array {
    $tx_types = ['BUY', 'SELL', 'SHORT', 'VAULT_LOCK', 'TOKENIZE'];

    // Lekérjük a valós usereket (Csak ID a GDPR miatt)
    $users = [];
    $res = $conn->query("SELECT id FROM users LIMIT 100");
    while ($row = $res->fetch_assoc()) $users[] = $row;
    if (empty($users)) $users = [['id' => 1], ['id' => 2], ['id' => 3], ['id' => 4]];

    // --- Pontos hierarchia betöltése a UI-ból ---
    $iso_map = [
        'United States' => 'USA', 'Canada' => 'CAN', 'United Kingdom' => 'GBR',
        'Germany' => 'DEU', 'France' => 'FRA', 'Italy' => 'ITA',
        'Spain' => 'ESP', 'Netherlands' => 'NLD', 'Switzerland' => 'CHE',
        'Sweden' => 'SWE', 'Japan' => 'JPN', 'South Korea' => 'KOR',
        'Australia' => 'AUS', 'New Zealand' => 'NZL', 'Singapore' => 'SGP',
        'UAE' => 'ARE', 'Brazil' => 'BRA', 'Mexico' => 'MEX',
        'Argentina' => 'ARG', 'Chile' => 'CHL', 'South Africa' => 'ZAF',
        'Hungary' => 'HUN', 'Austria' => 'AUT', 'Poland' => 'POL',
    ];

    $geography = [
        'North America' => ['United States' => ['New York', 'Los Angeles'], 'Canada' => ['Toronto']],
        'Europe' => ['United Kingdom' => ['London'], 'Germany' => ['Berlin'], 'France' => ['Paris'], 'Hungary' => ['Budapest']],
        'Asia' => ['Japan' => ['Tokyo'], 'South Korea' => ['Seoul']],
        'Middle East' => ['UAE' => ['Dubai']],
        'South America' => ['Brazil' => ['Sao Paulo']],
        'Oceania' => ['Australia' => ['Sydney']],
        'Africa' => ['South Africa' => ['Cape Town']]
    ];

    $abbrev = ['North America'=>'NA', 'Europe'=>'EU', 'Asia'=>'AS', 'Middle East'=>'ME', 'South America'=>'SA', 'Oceania'=>'OC', 'Africa'=>'AF'];
    $districtPool = ['NOR', 'SOU', 'EAS', 'WES', 'CEN', 'DOW', 'WAT', 'HIS', 'FIN', 'SUB'];

    // Dinamikusan generáljuk a valid tickereket minden szintre, egységes REX- prefixszel
    $cont_tickers = [];
    $nat_tickers = [];
    $leaf_tickers = [];

    foreach($geography as $continent => $countries) {
        $cont_pref = isset($abbrev[$continent]) ? $abbrev[$continent] : strtoupper(substr(str_replace(' ', '', $continent), 0, 2));
        $cont_tickers[] = "REX-{$cont_pref}-CONT-IDX";

        foreach($countries as $country => $cities) {
            $nat_pref = isset($iso_map[$country]) ? $iso_map[$country] : strtoupper(substr(str_replace(' ', '', $country), 0, 3));
            $nat_tickers[] = "REX-{$nat_pref}-IDX";

            foreach($cities as $city) {
                $city_pref = strtoupper(substr(str_replace(' ', '', $city), 0, 3));
                // Minden város kap egy fő tickert és pár véletlenszerű kerületet (Leaf)
                $leaf_tickers[] = "REX-{$nat_pref}-{$city_pref}";
                $leaf_tickers[] = "REX-{$nat_pref}-{$city_pref}-" . $districtPool[array_rand($districtPool)];
                $leaf_tickers[] = "REX-{$nat_pref}-{$city_pref}-" . $districtPool[array_rand($districtPool)];
            }
        }
    }

    $all_blocks_txs = [];
    for ($b = 0; $b < 10; $b++) {
        $num_tx = mt_rand(3, 6);
        $txs    = [];
        
        for ($t = 0; $t < $num_tx; $t++) {
            $type = $tx_types[array_rand($tx_types)];
            $user = $users[array_rand($users)];
            
            // Kockadobás a trade szintjére: 5% Global, 15% Continent, 30% National, 50% Leaf Node
            $trade_level_roll = mt_rand(1, 100);

            if ($trade_level_roll <= 5) {
                // Global Scale
                $ticker = 'REX-GLOBAL-IDX';
                $context = "(Global Aggregate)";
                $amount = mt_rand(10000000, 50000000);
                if ($type === 'TOKENIZE') $type = 'BUY'; // Indexet nem lehet tokenizálni
            } elseif ($trade_level_roll <= 20) {
                // Continental Scale
                $ticker = $cont_tickers[array_rand($cont_tickers)];
                $context = "(Continental Index)";
                $amount = mt_rand(1000000, 10000000);
                if ($type === 'TOKENIZE') $type = 'BUY';
            } elseif ($trade_level_roll <= 50) {
                // National Scale
                $ticker = $nat_tickers[array_rand($nat_tickers)];
                $context = "(National Index)";
                $amount = mt_rand(200000, 2000000);
                if ($type === 'TOKENIZE') $type = 'SHORT';
            } else {
                // Leaf Node
                $ticker = $leaf_tickers[array_rand($leaf_tickers)];
                $context = "(Local Leaf Node)";
                $amount = mt_rand(500, 150000);
            }

            $shares = round($amount / mt_rand(500, 5000), 4);

            // Tranzakció leírása (Teljesen letisztítva, csak "User")
            switch ($type) {
                case 'BUY':
                    $detail = "User Executed Buy: $" . number_format($amount) . " of {$ticker} {$context}";
                    break;
                case 'SELL':
                    $detail = "User Liquidated $" . number_format($amount) . " position in {$ticker} {$context}";
                    break;
                case 'SHORT':
                    $detail = "User Opened Short: $" . number_format($amount) . " against {$ticker} {$context}";
                    break;
                case 'VAULT_LOCK':
                    $detail = "User Locked $" . number_format($amount) . " of {$ticker} into Staking Vault";
                    break;
                case 'TOKENIZE':
                    $detail = "User Tokenized Asset: Hash 0x" . strtoupper(substr(md5(time().$t), 0, 6)) . " minted into {$ticker}";
                    break;
                default:
                    $detail = "Unknown transaction";
            }

            // Szigorú GDPR adatszerkezet
            $txs[] = [
                'tx_id'      => '0x' . strtoupper(substr(hash('sha256', uniqid('', true)), 0, 16)),
                'type'       => $type,
                'user_id'    => $user['id'],
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

    $conn->query("TRUNCATE TABLE blockchain_ledger");

    $blockchain = new Blockchain(difficulty: 1);
    $all_blocks = generateDummyTransactions($conn);

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

    // Genesis blokk mentése
    $genesis = $blockchain->getChain()[0];
    $g_json  = json_encode($genesis->transactions, JSON_UNESCAPED_UNICODE);
    
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

    return [
        'valid'        => $blockchain->isValid(),
        'block_count'  => count($blockchain->getChain()),
        'tx_count'     => array_sum(array_map(fn($b) => count($b->transactions), $blockchain->getChain())),
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

        if ($curr['previous_hash'] !== $prev['block_hash']) {
            return ['valid' => false, 'reason' => "Block #{$curr['block_index']}: previous_hash mismatch"];
        }

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