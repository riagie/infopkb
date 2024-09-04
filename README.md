## Motorized vehicle tax information
 Vehicle Tax Check API is an application programming interface that allows you to check vehicle tax status and information in real-time using the vehicle's police number. This API is designed to provide convenience in obtaining vehicle tax-related data quickly and accurately.

#### Information results
```
{
    "RC": status,
    "RCM": "messages"
    "DATA": "result"
}
```

#### Endpoints
Check Endpoints
- `{url}`
- response 
```
{
    "/detail/{nopol}": {
        "method": "GET",
        "parameters": {
            "nopol": "B1234XYZ"
        }
    },
    "/regional": {
        "method": "GET"
    }
}
```