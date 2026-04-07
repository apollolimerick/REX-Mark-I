# REX AMM Physics Simulator

## Overview
This file contains the **Automated Market Maker (AMM) Physics Simulator**, an interactive, session-based PHP application. It is designed to visualize and test the mathematical mechanics of a constant-product liquidity pool. Users can initialize a genesis pool, execute simulated trades (buys and sells) using either fiat (USD) or property shares, and observe the real-time impact on price, slippage, and the bonding curve.

## Core Functionalities

* **Genesis Pool Initialization:** Allows users to bootstrap a new market by defining initial property shares and cash reserves, which locks in the constant pool equation ($k = x \cdot y$).
* **Dual-Input Trading Engine:** Processes user orders based on either exact USD amounts or exact Share amounts, calculating the exact outputs required to maintain the pool's mathematical balance.
* **Safety Constraints:** Automatically prevents "bank runs" (extracting more cash than the pool holds) and "inventory depletion" (buying more shares than are available).
* **Mathematical Engine Breakdown:** Provides a live, on-screen algebraic breakdown of the spot price, average execution price, and price impact (slippage) for the most recent transaction.
* **Live Bonding Curve Visualization:** Uses Chart.js to render a dynamic scatter plot of the pool's current state against the ideal $y = \frac{k}{x}$ bonding curve.
* **Transaction Ledger:** Maintains a session-persistent history of all executed trades, logging time, action type, exchanged amounts, execution price, and total price impact.

## Mathematical Models

The simulator's logic is driven by the standard constant-product mathematical model. The core equations enforced by the PHP engine are:

| Metric | Formula | Description |
| :--- | :--- | :--- |
| **Constant Product** | $x \cdot y = k$ | The foundational rule of the AMM. The product of shares ($x$) and cash ($y$) must remain constant ($k$) after every trade. |
| **Spot Price** | $P = \frac{y}{x}$ | The theoretical price of a single share at the current pool state. |
| **Execution Price** | $P_{avg} = \frac{\Delta y}{\Delta x}$ | The actual average price paid (or received) per share during a specific transaction. |
| **Slippage Ratio** | $Sl = \left\vert \frac{P_{avg}}{P_0} - 1 \right\vert$ | The universal formula calculating the percentage deviation between the execution price and the initial spot price. |

## Architecture & Tech Stack

* **State Management:** Utilizes raw PHP `$_SESSION` variables to persist the AMM state ($x$, $y$, $k$), transaction history, and mathematical snapshots across page reloads without requiring a database.
* **Frontend Design:** Built with raw HTML/CSS inside the PHP file, featuring a dark-mode UI, frosted glass card effects, dynamic percentage bars for inventory visualization, and responsive grid layouts.
* **Data Visualization:** Integrates the Chart.js CDN to plot the mathematical bonding curve dynamically using JavaScript, injecting real-time PHP state variables into the client-side chart generation.
