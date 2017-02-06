# brownlife

A simple resolutions tracker.

Currently it just displays a bunch of days and a bunch of sins user wants to put an end to.

There is a matrix of count of sins &times; count of days the resolution is valid for. The matrix constists of clickable squares. When clicked, the square changes its colour, meaning the objective has been satisfied for the day.

Submitted data are stored in 'mockDB' a file, that simulates a very simple database. Therefore make sure the user who runs the script (web-server) is allowed to create files within a directory script resides in. On first run the mockDB file is created automatically.

In order to use it these lines have to be adjusted in order to set the thresholds:

```
$now = new DateTime('2016-11-28');
$threshold = new DateTime('2017-11-28');
```
