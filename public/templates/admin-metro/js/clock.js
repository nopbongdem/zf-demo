
function clock() {
    // Khoi tao doi tuong thoi gian.
    var timer = new Date();
    var hour = timer.getHours();
    var minute = timer.getMinutes();
    var second = timer.getSeconds();
    if (hour < 10) {
        hour = "0" + hour;
    }
    if (minute < 10) {
        minute = "0" + minute;
    }
    if (second < 10) {
        second = "0" + second;
    }
    document.getElementById("clock").innerHTML = hour + ":" + minute + ":" + second;
}
setInterval("clock()", 1000);
