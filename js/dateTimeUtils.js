const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
function timeConvert(time) {
    time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
    if (time.length > 1) {
        time = time.slice(1);
        time[5] = +time[0] < 12 ? ' AM' : ' PM';
        time[0] = +time[0] % 12 || 12;
    }
    return time.join('');
}
function dateConvert(date){
    var strOfDate= new Date(date);
    return days[strOfDate.getDay()] + ", " + monthNames[strOfDate.getUTCMonth()] + " " + strOfDate.getUTCDate() + ", " + strOfDate.getUTCFullYear();
}
function noWeekDaysDateConvert(date){
    var strOfDate= new Date(date);
    return monthNames[strOfDate.getUTCMonth()] + " " + strOfDate.getUTCDate() + ", " + strOfDate.getUTCFullYear();
}