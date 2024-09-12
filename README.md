## Motorized vehicle tax information
 Vehicle Tax Check API is an application programming interface that allows you to check vehicle tax status and information in real-time using the vehicle's police number. This API is designed to provide convenience in obtaining vehicle tax-related data quickly and accurately.

#### Information results
```
{
    "RC": "0200",
    "RCM": "SUCCESS",
    "DATA": {
        "MERK": "YAMAHA",
        "MODEL": "T 105 ER VEGA R",
        "JENIS": "SEPEDA MOTOR",
        "TAHUN": 2004,
        "WARNA": "HITAM",
        "CC": 102,
        "BBM": "BENSIN",
        "PLAT": "PUTIH",
        "NO_POLISI": "XXXXXXXX",
        "NO_RANGKA": "MH3XXXXXXXXXXXXXXXXX0",
        "NO_MESIN": "4STXXXXXXXXXXX7",
        "NAMA_PEMILIK": "SXXXXXXXXXXXXXXXN",
        "ALAMAT": "PT.XXXXXXXXXXXXXXXXXXXXXXXXXIM",
        "MILIK_KE": "",
        "WILAYAH": "",
        "TGL_PAJAK": "20120330",
        "TGL_STNK": "20140330",
        "PKB_POKOK": 7200000,
        "PKB_DENDA": 72000,
        "SWDKLLJ_POKOK": 540000,
        "SWDKLLJ_DENDA": 78900,
        "PNPB_STNK": 100000,
        "PNPB_DENDA": 60000,
        "TOTAL": 1097900
    }
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