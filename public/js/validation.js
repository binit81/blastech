  
    $(document).ready(function () {
    $('#form').validate({ // initialize the plugin
        rules: {
            category: {
                required: true
            },
            jobtype: {
                required: true
            },
            description: {
                required: true
//                mini: 10,
//                max: 10000
            },
            state: {
                required: true
                
            },
            city: {
                required: true
            },
            minisalary: {
                required: true,
                gte: 1
                
            },
            maxisalary: {
                required: true,
                gte: 'input[name="minisalary"]'
                
            },
            miniexp: {
                required: true,
                gte: 0
            },
             mixexp: {
                required: true,
                gte: 'input[name="miniexp"]'
            },
            jobfunction: {
                required: true
            }
        }
    });
});
// When the document is ready
//use for date    
$('.calendar').datepicker({
  format: "yyyy/mm/dd"
});    

