import moment from 'moment'

var $locale = window.Laravel.locale
var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
var localeSupport = ['LT', 'LTS', 'L', 'l', 'LL', 'll', 'LLL', 'lll', 'LLLL', 'llll'];
export default class DateFormatter{

	getDays(){
        return days
    }

    /*
     * This function is mostly use to format date as of now
     * (12/12/2018).
    */
    formatDate(date, format, origFormat = null){
        if(date){
            if(localeSupport.indexOf(format) >= 0){
                moment.locale($locale)
            }
            if(origFormat){
                let isValid = moment(date, origFormat).isValid();
                if(isValid){
                    return moment(date, origFormat).format(format)
                }
            }
            else{
                return moment(date).format(format)
            }
        }
        return null
    }
}
