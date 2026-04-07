# REX External Valuation Engines (Python & C#)

## Overview
These three files (`test_valuation.py`, `test_valuation.cs`, and its compiled `test_valuation.exe`) act as placeholder quantitative engines. They are designed to be executed via terminal commands from the primary PHP backend, demonstrating how the platform offloads complex algorithmic processing (like automated valuation models or machine learning tasks) to specialized external scripts via Inter-Process Communication (IPC).

## Core Functionalities

* **Command-Line Argument Parsing:** Both scripts capture the target property's `cadastral_id` directly from the system arguments passed during the shell execution command.
* **Algorithmic Simulation:** Generates a randomized, realistic property valuation floating-point number bounded between $150,000.00 and $2,500,000.00, rounded to two decimal places to mimic fiat currency.
* **Strict JSON Formatting:** Packages the execution status, the passed property ID, and the calculated valuation into a strictly formatted JSON payload. The C# implementation achieves this manually without external dependencies to ensure minimal overhead.
* **Standard Output Broadcasting:** Pushes the finalized JSON string to the system's standard output (`stdout`), allowing the calling PHP wrapper to capture, decode, and integrate the data seamlessly.

## Engine File Breakdown

| File Name | Language / Type | Purpose |
| :--- | :--- | :--- |
| **`test_valuation.py`** | Python Script | Represents a data-science or machine-learning oriented valuation model. Requires a Python interpreter to run via the system shell. |
| **`test_valuation.cs`** | C# Script | The raw source code for the highly optimized, strictly typed valuation engine utilizing `System` and `CultureInfo.InvariantCulture`. |
| **`test_valuation.exe`** | Compiled Executable | The compiled Windows binary of the C# source. Executes natively without an interpreter, offering the highest performance for rapid, bulk valuation queries. |

## Input & Output Specification

Regardless of the language or execution method (Interpreter vs. Compiled Binary), all engines adhere to the exact same I/O contract to ensure the PHP bridge rarely breaks.

**Input Argument:**
A single string representing the internal property identifier (e.g., `REX-LOT-9942`). Defaults to `UNKNOWN-LOT` if no argument is detected.

**Expected `stdout` Output:**
```json
{
  "status": "success",
  "cadastral_id": "REX-LOT-9942",
  "estimated_value": 1450250.75
}
```
