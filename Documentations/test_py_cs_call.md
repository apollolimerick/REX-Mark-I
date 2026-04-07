# REX Cross-Language Valuation Wrappers

## Overview
This file serves as the **Inter-Process Communication (IPC) Bridge** for the REX platform. It demonstrates how the primary PHP backend securely delegates complex property valuation calculations to external, specialized analytical engines written in Python and C#. 

## Core Functionalities

* **Python Engine Wrapper (`getPythonValuation`):** Constructs and executes a shell command to run a Python script (`test_valuation.py`). This architecture is designed to integrate Python's robust data science and machine learning ecosystems into the standard PHP workflow.
* **C# Engine Wrapper (`getCSharpValuation`):** Directly invokes a compiled C# executable (`test_valuation.exe`). This pathway is utilized for high-performance, strictly typed algorithmic processing where execution speed and memory management are critical.
* **Standardized JSON Parsing:** Both wrappers capture the standard output (`stdout`) of the external scripts, anticipating a strictly formatted JSON response. The PHP engine decodes this JSON to seamlessly integrate the calculated valuation data back into the web application's native state.
* **Automated Execution Testing:** The bottom half of the script contains a lightweight HTML testing block that injects a dummy property ID (`REX-LOT-9942`) into both wrappers to verify cross-language execution, pathing, and output parsing.

## Security & Execution Mechanics

| Mechanism | Description |
| :--- | :--- |
| **`escapeshellarg()`** | A critical security measure applied to the incoming `$property_id` before system execution. It adds quotes around the string and escapes existing quotes, mitigating OS command injection attacks when passing dynamic variables to the system shell. |
| **`shell_exec()`** | The native PHP function used to execute the constructed commands via the host operating system's terminal. It blocks PHP execution until the external script terminates, returning the complete output as a string. |
| **Error Handling** | The wrappers include strict validation gates: ensuring the shell execution did not return `null`, verifying the JSON decoding process threw no syntax errors (`JSON_ERROR_NONE`), and checking for a discrete `'status' === 'success'` flag in the parsed payload. |

## Architecture Context
This modular, decoupled architecture allows the REX platform to remain language-agnostic at the algorithmic level. The web server (PHP) handles session state, secure routing, and database interactions, while safely offloading heavy quantitative financial modeling to dedicated, language-optimized micro-scripts.
