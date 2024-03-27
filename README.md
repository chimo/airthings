# airthings

Simple PHP script to GET samples from the [Airthings
API](https://developer.airthings.com/docs/consumer/api/getting-started) for a
specific device.

It currently has a single endpoint, which expects a "Secrets" HTTP header:  
`wget -q -O- --header 'Secret: xxx' https://example.org`

An Alpine Linux APKBUILD is available for it at
[~chimo/apkbuilds/airthings](https://git.srht.chromic.org/~chimo/apkbuilds/tree/master/item/airthings).

## Depenencies

* PHP APCU extension (php-pecl-apcu)
* PHP cURL extension (php-curl)
* PHP JSON extension (php-json)

## Configuration

See the
"[config.dist.php](https://git.srht.chromic.org/~chimo/airthings/tree/main/item/private/config.dist.php)"
file in the "private" folder for configuration options. Rename or copy it as
"config.php" and replace the contents with your values.


## Output

```
{
    "time": 1711489008,
    "battery": 72,
    "co2": 541,
    "humidity": 32,
    "pressure": 1004.2,
    "radonShortTermAvg": 16,
    "relayDeviceType": "app",
    "temp": 19.1,
    "voc": 170
}
```

