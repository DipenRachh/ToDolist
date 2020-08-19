<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 <link rel="stylesheet" href="stopwatch.scss">
<!------ Include the above in your HEAD tag ---------->
<style type="text/css">
body{
    background-color:#EEEEEE;
}
.stopwatch {
    width: 300px;
    background-color: #0af;
    border-radius: 5px;
    box-shadow: 0 4px rgba(0, 0, 0, 0.75), 0 0 1px rgba(0, 0, 0, 0.15);
    padding: 15px;

    &, & * {
        transition: all 0.15s ease-out;
    }

    .controls {
        display: flex;

        button {
            flex-grow: 1;
            margin: 0 5px 4px;
            padding: 5px 0;
            border-radius: 5px;
            box-shadow: 0 4px rgba(0, 0, 0, 0.75);
            border: 0;
            outline: 0;
            font-size: 16px;
            color: white;
            cursor: pointer;
            font-weight: bold;

            &:active {
                margin-bottom: 0;
                margin-top: 4px;
                box-shadow: none;
            }
        }

        .start {
            background-color: #5d5;

            &:hover {
                background-color: #6e6;
            }
        }

        .stop {
            background-color: #d55;

            &:hover {
                background-color: #e66;
            }
        }

        .reset {
            background-color: #55d;

            &:hover {
                background-color: #66e;
            }
        }
    }

    .display {
        font-size: 50px;
        font-family: sans-serif;
        text-align: center;
        margin-top: 10px;

        :not(:last-child):after {
            content: ':';
        }
    }
}
.todolist{
    background-color:#FFF;
    padding:20px 20px 10px 20px;
    margin-top:30px;
}
.todolist h1{
    margin:0;
    padding-bottom:20px;
    text-align:center;
}
.form-control{
    border-radius:0;
}
li.ui-state-default{
    background:#fff;
    border:none;
    border-bottom:1px solid #ddd;
}

li.ui-state-default:last-child{
    border-bottom:none;
}

.todo-footer{
    background-color:#F4FCE8;
    margin:0 -20px -10px -20px;
    padding: 10px 20px;
}
#done-items li{
    padding:10px 0;
    border-bottom:1px solid #ddd;
   
}

#done-items li:last-child{
    border-bottom:none;
}
#checkAll{
    margin-top:10px;
}
</style>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="todolist not-done">
             <h1>Todo List</h1>
             <h4 style="color: lightblue" >Add Task</h4>
                <input type="text" required=""
                class="form-control add-todo" placeholder="Press enter for submit task name">
                    <br/>
                    
                    <hr>
                    <ul id="sortable" class="list-unstyled">
                   <h3>  Task List </h3>
                   <h6 style="color:blue"> Select check box when done task</h6>
                </ul>
                <div class="todo-footer">
                    <strong><span class="count-todos"></span></strong> Tasks Left
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="todolist">
             <h1>Task Done</h1>
                <ul id="done-items" class="list-unstyled">
                    
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
$("#sortable").sortable();
$("#sortable").disableSelection();

countTodos();

// all done btn
$("#checkAll").click(function(){
    AllDone();
});

//create todo
$('.add-todo').on('keypress',function (e) {
      e.preventDefault
      if (e.which == 13) {
           if($(this).val() != ''){
           var todo = $(this).val();
            createTodo(todo); 
            countTodos();
           }else{
               // some validation
           }
      }
});
// mark task as done
$('.todolist').on('change','#sortable li input[type="checkbox"]',function(){
    if($(this).prop('checked')){
        var doneItem = $(this).parent().parent().find('label').text();
        $(this).parent().parent().parent().addClass('remove');
        done(doneItem);
        countTodos();
    }
});

//delete done task from "already done"
$('.todolist').on('click','.remove-item',function(){
    removeItem(this);
});

// count tasks
function countTodos(){
    var count = $("#sortable li").length;
    $('.count-todos').html(count);
}

//create task
function createTodo(text){
    var markup = '<li class="ui-state-default"><div class="checkbox"><label><input type="checkbox" value="" />'+ text +'</label> &nbsp <div class="stopwatch"><div class="controls"><button class="start">Start</button> &nbsp <button class="stop">Stop</button></div> &nbsp <div class="display"><label><span class="minutes">00</span>:<span class="seconds">00</span>:<span class="centiseconds">00</span></label></div></div></div></li> ';
    $('#sortable').append(markup);
    $('.add-todo').val('');
   timer();
}

function timer(){
	 var ss = document.getElementsByClassName('stopwatch');

[].forEach.call(ss, function (s) {
    var currentTimer = 0,
        interval = 0,
        lastUpdateTime = new Date().getTime(),
        start = s.querySelector('button.start'),
        stop = s.querySelector('button.stop'),
        //reset = s.querySelector('button.reset'),
        mins = s.querySelector('span.minutes'),
        secs = s.querySelector('span.seconds'),
        cents = s.querySelector('span.centiseconds');

    start.addEventListener('click', startTimer);
    stop.addEventListener('click', stopTimer);
    //reset.addEventListener('click', resetTimer);

    function pad (n) {
        return ('00' + n).substr(-2);
    }

    function update () {
        var now = new Date().getTime(),
            dt = now - lastUpdateTime;

        currentTimer += dt;

        var time = new Date(currentTimer);

        mins.innerHTML = pad(time.getUTCMinutes());
        secs.innerHTML = pad(time.getSeconds());
        cents.innerHTML = pad(Math.floor(time.getMilliseconds() / 10));

        lastUpdateTime = now;
    }

    function startTimer () {
        if (!interval) {
            lastUpdateTime = new Date().getTime();
            interval = setInterval(update, 1);
        }
    }

    function stopTimer () {
        clearInterval(interval);
        interval = 0;
    }


});

}

//mark task as done
function done(doneItem){
    var done = doneItem;
    var markup = '<li>'+ done +'<button class="btn btn-default btn-xs pull-right  remove-item"><span class="glyphicon glyphicon-remove"></span></button></li>';

    $('#done-items').append(markup);
     var words = ['Start', 'Stop'];

// selecting the relevant elements:
$('#done-items.li').filter(function() {
  // the text contained within the <a> element, with
  // leading/trailing white-space (if any) removed:
  var text = $(this).find('a').text().trim();

  // if the words array contains the found text:
  return words.indexOf(text) > -1;
// we hide the element (though remove() could be called):
}).hide();
    $('.remove').remove();

}

//mark all tasks as done
function AllDone(){
    var myArray = [];

    $('#sortable li').each( function() {
         myArray.push($(this).text());   
    });
    
    // add to done
    for (i = 0; i < myArray.length; i++) {
        $('#done-items').append('<li>' + myArray[i] + '<button class="btn btn-default btn-xs pull-right  remove-item"><span class="glyphicon glyphicon-remove"></span></button></li>');
    }
    
    // myArray
    $('#sortable li').remove();
    countTodos();
}

//remove done task from list
function removeItem(element){
    $(element).parent().remove();
}
 

</script>
