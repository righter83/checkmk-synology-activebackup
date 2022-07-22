# checkmk-synology-activebackup
## Info

This scripts is written for CheckMK.
It uses the SQLite Databases to get the job results for the Synology Active Backups.
Tested on:
- CheckMK 2.0
- Synology DSM 7.1
- Active Backup for Business 2.4.1

## Install
* Copy the scripty to your Synology NAS and make it excecutable.
* Install the SSH Key from CheckMK on your NAS.
* Afterwards you have to configure the Check in CheckMK:
Go to Services -> Other Services and add a "Check via SSH service"
Configure everything for your NAS and the command should be like:
```/volume1/check_ab.php```

## Config modifications
There is also a check inside which checks if the job was running in a specific time window.
If you run a job daily you should adjust the config variable runtime to 86500 -> a little bit higher as one day.
You can also disable this check if you set the runtimecheck variable to False

## Outputs
![](https://github.com/righter83/checkmk-synology-activebackup/tree/main/images/ok.png)

![](https://github.com/righter83/checkmk-synology-activebackup/images/error.png)
