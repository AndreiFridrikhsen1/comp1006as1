
// get all the buttons
let buttons = document.querySelectorAll(".button");
// add even listener to each buttom
buttons.forEach(function(button){
    button.addEventListener("click",function(e){
        if(confirm('Are you sure')== false){
            // preven the link from opening
            e.preventDefault();
        }
    });
});