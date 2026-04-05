using System;
using System.Globalization;

class Program
{
    static void Main(string[] args)
    {
        // Grab the ID sent from PHP
        string cadastralId = args.Length > 0 ? args[0] : "UNKNOWN-LOT";

        // Generate the random price
        Random random = new Random();
        double randomPrice = random.NextDouble() * (2500000.0 - 150000.0) + 150000.0;
        randomPrice = Math.Round(randomPrice, 2);

        // Manually format the string as JSON (No external libraries needed!)
        string jsonOutput = string.Format(CultureInfo.InvariantCulture,
            "{{\"status\":\"success\",\"cadastral_id\":\"{0}\",\"estimated_value\":{1}}}", 
            cadastralId, randomPrice);

        // Print to PHP
        Console.WriteLine(jsonOutput);
    }
}