import sys
import json
import random

# Grab the ID sent from PHP (or use a fallback if missing)
cadastral_id = sys.argv[1] if len(sys.argv) > 1 else "UNKNOWN-LOT"

# Generate a random home price (e.g., between $150k and $2.5m)
# round(..., 2) ensures it formats like normal currency
random_price = round(random.uniform(150000.00, 2500000.00), 2)

# Package the result into a dictionary
result = {
    "status": "success",
    "cadastral_id": cadastral_id,
    "estimated_value": random_price
}

# Print the JSON string (This is what PHP will capture)
print(json.dumps(result))