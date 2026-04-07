# REX Global Asset Terminal

## Overview
This file serves as the primary user interface, routing controller, and data simulation engine for the **REX (Residential Property Exchange)** platform. It handles user authentication, generates hierarchical global market indices, manages complex state routing via URL parameters, and renders advanced data visualizations including interactive charts, heatmaps, and a 3D WebGL globe.

## Core Modules & Functionality

### 1. Authentication Handler
Manages secure user sessions and identity verification.
* **Login / Logout:** Validates user credentials against the `users` database table using `password_verify()` and manages PHP session state.
* **Registration:** Hashes new passwords via `password_hash(..., PASSWORD_DEFAULT)` and provisions new investor/homeowner profiles.

### 2. Generative Market Database Engine
Dynamically constructs a hierarchical, multi-tiered virtual economy.
* **Geographic Taxonomy:** Builds a structured nested array: Continent &rarr; Country &rarr; City &rarr; District.
* **Aggregate Indices:** A recursive function (`buildIndices`) calculates volume-weighted average prices and percentage changes at every node level, rolling up individual property data into city, country, continental, and finally the `REX-GLOBAL-IDX` ticker.
* **Procedural Generation:** Seeds random number generators with deterministic hashes (e.g., `crc32()`) to ensure the generated mock data remains stable and consistent across page reloads for specific tickers.

### 3. View Management & Routing Logic
Utilizes a custom, parameter-based routing system (`?view=` and `?path=`) to navigate the complex market tree.

| View Parameter | Purpose | Description |
| :--- | :--- | :--- |
| `terminal` | Market Explorer | The default view. Navigates the geographic hierarchy, displays aggregate indices, and reveals specific properties backed by a district node. |
| `offer` | Underwriting Entry | Displays the landing page directing homeowners to the property tokenization and underwriting questionnaire. |
| `portfolio` | Asset Management | Renders the user's mock portfolio, separating assets into Liquid Equity and Vaulted (Locked) Equity, alongside historical P/L charts. |
| `chain` | Blockchain Explorer | A real-time cryptographic ledger displaying simulated on-chain settlements (Buys, Sells, Shorts, Locks) with filtering capabilities. |
| `advanced` | Market Intelligence | Displays a price-weighted momentum treemap (Heatmap) and algorithmic sentiment rankings for rapid market analysis. |
| `about` | Platform Overview | Informational view explaining the REX protocol, fractionalization mechanics, and infrastructure partners. |

### 4. Interactive Trading Simulation
When a user navigates to a tradable leaf node (a district or city without sub-regions), the platform activates the Trade Panel overlay.
* **Order Types:** Simulates Buy, Sell, Short (with margin requirement calculations), and Vault (time-locked staking with varying APY yields).
* **Dynamic Conversions:** Bi-directionally calculates USD-to-Shares and Shares-to-USD values based on the live spot price of the selected index.

## Architecture & Frontend Tech Stack

* **Styling:** Utilizes a custom configuration of **Tailwind CSS** delivered via CDN, paired with a heavily customized "Glassmorphism" UI featuring dynamic blur overlays, responsive grids, and strict light/dark mode implementations.
* **Data Visualization (2D):** Integrates **Chart.js** to render real-time, interactive line and candlestick charts for asset trajectories, as well as the user's historical portfolio value.
* **Data Visualization (3D):** Implements **Globe.gl** (built on Three.js) to render an interactive, rotatable 3D Earth. It plots geographic coordinates for active markets and draws animated settlement arcs to represent global liquidity flow.
