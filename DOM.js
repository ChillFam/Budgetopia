function home() {
    //percents
    document.getElementById("nPercent").innerHTML = 
    "Needs: " + "*needs percent*" + "%";
    document.getElementById("wPercent").innerHTML = 
    "Wants: " + "*wants percent*" + "%";
    document.getElementById("sPercent").innerHTML = 
    "Savings: " + "*savings percent*" + "%";
    //budgeted
    document.getElementById("nBudgeted").innerHTML = 
    "Budgeted: $" + "*needs budgeted*";
    document.getElementById("wBudgeted").innerHTML = 
    "Budgeted: $" + "*wants budgeted*";
    document.getElementById("sBudgeted").innerHTML = 
    "Budgeted: $" + "*savings budgeted*";
    //remaining
    document.getElementById("nRemain").innerHTML = 
    "Remaining: $" + "*needs remaining (budgeted-expenses)*";
    document.getElementById("wRemain").innerHTML = 
    "Remaining: $" + "*wants remaining (budgeted-expenses)*";
}