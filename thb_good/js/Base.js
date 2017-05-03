//设置鼠标移入移出方法
Base.prototype.hover = function(over,out){
    // body...
    for(var i =0; i<this.elements.length; i++){
        this.elements[i].onmouseover = over;
        this.elements[i].onmouseout = out;
    }
    return this;
};