# brownlife

A simple resolutions tracker.

### Description

Currently it just displays a bunch of days and a bunch of sins user wants to put an end to.

There is a matrix of count of sins &times; count of days the resolution is valid for. The matrix consists of clickable squares. When clicked, the square changes its colour, meaning the objective has been satisfied for the day.

Submitted data are stored in _mockDB_ a file, that simulates a very simple database. Therefore make sure the user who runs the script (web-server) is allowed to create files within a directory the script resides in. On first run the _mockDB_ file is created automatically.

### In order to use it do this

1/ copy password.cfg.sample into password.cfg and change the password stored there

```
$ cp ./password.cfg.sample ./password.cfg
$ echo your-new-password > ./password.cfg
```

2/ adjust these lines of `index.php` file to set start and end dates:

```
$now = new DateTime('2016-11-28');
$threshold = new DateTime('2017-11-28');
```

### Example of rendered and utilized tracker

![Brownlife rendered web](https://raw.githubusercontent.com/helvete/brownlife/master/bl.png)
