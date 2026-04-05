# Integrating C# Executables with PHP via JSON

This document explains how to write a lightweight C# script, compile it into a standalone executable (`.exe`) using Windows' built-in tools, and successfully pass data from that executable back to a PHP web application using JSON.

---

## 1. The Compilation Process (Understanding the Command Line)

If you do not want to install massive development environments like Visual Studio or the modern .NET SDK, Windows actually has a C# compiler hidden inside its system files by default. It is called **`csc.exe`**.

1. **The Path:** The user navigates directly to the project folder (`cd C:\xampp\htdocs\REX\CS_test_call`).
2. **The Command:** The built-in compiler is triggered using its absolute path (`C:\Windows\Microsoft.NET\Framework\v4.0.30319\csc.exe`), pointing to the target C# file (`Test_Valuation.cs`).
3. **The Output:** The warning `This compiler is provided as part of the Microsoft (R) .NET Framework, but only supports language versions up to C# 5...` is a **success message**. It simply informs you that it is using an older, highly stable version of C#. 

Because the command was run from inside the `REX` directory, the compiler instantly drops a brand new, fully functional `Test_Valuation.exe` directly into that exact same folder.

---

**Commands to enter:**

    cd C:\xampp\htdocs\REX\CS_test_call
```
C:\Windows\Microsoft.NET\Framework\v4.0.30319\csc.exe C:\xampp\htdocs\REX\CS_test_call\Test_Valuation.cs
