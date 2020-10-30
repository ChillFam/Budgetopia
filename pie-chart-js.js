class PChartElement extends HTMLElement {
    connectedCallback() {
    }
}

class PChart extends HTMLElement {
    shorten_text = function (text, max_chars) {
        var return_val = ""
        if (text.length > max_chars) {
            return text.substr(0,max_chars) + "...";
        } else {
            return text
        }
    }

    totalFunc = function (total, num) {
        return total + num;
    }

    getPoint = function (c1,c2,radius,angle){
        return [c1+Math.cos(angle)*radius,c2+Math.sin(angle)*radius];
    }
    getAngle = function(x_coord,y_coord,x_origin,y_origin) {
        var radius = Math.sqrt(Math.pow(Math.abs(x_coord-x_origin),2) + Math.pow(Math.abs(y_coord-y_origin),2));
        if (Math.acos(Math.abs(x_coord-x_origin)/radius).toFixed(6) == Math.asin(Math.abs(y_coord-y_origin)/radius).toFixed(6) && radius <=this.radius)
        {
            var theta = Math.acos(Math.abs(x_coord-x_origin)/radius);
            if (x_coord - x_origin > 0 && y_coord - y_origin < 0) {
                return (2 * Math.PI) - theta;
            }
            if (x_coord - x_origin < 0 && y_coord - y_origin < 0) {
                return (Math.PI) + theta;
            }
            if (x_coord - x_origin < 0 && y_coord - y_origin > 0) {
                return (Math.PI) - theta;
            }
            if (x_coord - x_origin > 0 && y_coord - y_origin > 0) {
                return theta;
            }
        } else {
            return null;
        }
    }
    drawChart = function() {
        this.arcs = []
        var elements = this.getElementsByTagName("pchart-element");
        this.names = [];
        this.vals = [];
        this.colours = [];
        for (i = 0; i < elements.length; i++)
        {
            this.names.push(elements[i].getAttribute("name"));
            this.vals.push(parseFloat(elements[i].getAttribute("value")));
            this.colours.push(elements[i].getAttribute("colour"))
        }
        var shadow = this.shadowRoot;
        var c = this.shadowRoot.querySelector("canvas");
        var positionInfo = this.getBoundingClientRect();
        var height = positionInfo.height;
        var width = positionInfo.width;
        c.width  = width;
        c.height = height;
        var ctx = c.getContext("2d");

        var font_size = parseInt(width * 0.02166847);

        var max_font_size = 28;

        if (font_size > max_font_size) {
            font_size = max_font_size;
        }


        this.y_origin = height * 0.5;
        this.x_origin = c.width * 0.5;
        this.radius = c.width * 0.35;
        var max_radius = c.height * 0.35;
        if (this.radius > max_radius) {
            this.radius = max_radius;
        }

        var share_percentage = [];

        var x = c.width - parseInt(c.width * 0.12019231) * 1.25;
        var y = font_size;

        ctx.beginPath();
        var rect_height = (this.names.length * font_size) + 20;
        ctx.rect(x, y+10, parseInt(c.width * 0.12019231), rect_height);
        ctx.stroke();
        y += 10;
        for (var i=0;i<this.names.length;i++)
        {
            var percentage = this.vals[i] / this.vals.reduce(this.totalFunc);
            share_percentage.push(percentage);

            y += font_size;
            ctx.beginPath();
            ctx.fillStyle = this.colours[i];
            ctx.fillRect(x + 5, y-font_size/2, 5, 5);

            ctx.font = String(font_size) + "px Arial";
            var shorten = 0;
            if (ctx.measureText(this.names[i]).width > parseInt(c.width * 0.12019231)-15) {
                while(ctx.measureText(this.shorten_text(this.names[i],this.names[i].length - shorten)).width > (parseInt(c.width * 0.12019231)-15) && this.names[i].length - shorten>0) {
                    shorten++;
                }
            }
            ctx.fillText(this.shorten_text(this.names[i],this.names[i].length - shorten), x + 15, y);
            ctx.closePath();
        }
        for (var i = 0; i < share_percentage.length; i++)
        {
            if (i == 0)
            {
                start = 0;
            } else {
                var start = share_percentage.slice(0,i).reduce(this.totalFunc);
            }
            var end = (start + share_percentage[i]);
            ctx.beginPath();
            ctx.arc(this.x_origin, this.y_origin, this.radius, start * 2 * Math.PI, end * 2 * Math.PI);
            var arc_start = this.getPoint(this.x_origin,this.y_origin,this.radius,start * 2 * Math.PI);
            var arc_end = this.getPoint(this.x_origin,this.y_origin,this.radius,end * 2 * Math.PI);
            this.arcs.push([start * 2 * Math.PI, end * 2 * Math.PI]);
            var arc_middle = this.getPoint(this.x_origin,this.y_origin,this.radius+10,((start + end)) * Math.PI);
            ctx.lineTo(this.x_origin,this.y_origin);
            ctx.lineTo(arc_start[0],arc_start[1]);
            ctx.fillStyle = this.colours[i];
            ctx.fill();
            ctx.fillStyle = this.colours[i];
            if (arc_middle[0].toFixed(2) < this.x_origin)
            {
                ctx.textAlign = "end";
            } else {
                if (arc_middle[0].toFixed(2) == this.x_origin)
                {
                    ctx.textAlign = "center";
                } else {
                    ctx.textAlign = "start";
                }
            }
            if (arc_middle[1] > this.y_origin)
            {
                arc_middle = this.getPoint(this.x_origin,this.y_origin,this.radius+25,((start + end)) * Math.PI);
            }
            ctx.moveTo(arc_middle[0],arc_middle[1]);
            ctx.fillText(this.vals[i].toFixed(2), arc_middle[0], arc_middle[1]);
            ctx.closePath();
        }
    }
    resize = function() {
        var c = this.shadowRoot.querySelector("canvas");
        var positionInfo = this.getBoundingClientRect();
        var height = positionInfo.height;
        var width = positionInfo.width;
        c.width  = width;
        c.height = height;
        this.drawChart();
    }

}

//var pchart_element = Object.create(HTMLElement.prototype);
window.customElements.define('pchart-element',PChartElement);
window.customElements.define('pie-chart',PChart);
