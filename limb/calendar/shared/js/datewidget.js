var DATE_WIDGETS = new Object();
function DateWidget_Init(name, show_default)
{
	DATE_WIDGETS[name] = new DateWidget(name, show_default);
	DATE_WIDGETS[name].init();
}

function DateWidget_Action(name, action)
{
	var widget = DATE_WIDGETS[name];
	switch (action)
	{
		case "handle_change":
			DATE_WIDGETS[name].handle_change(DateWidget_Action.arguments[2]);
		break;
		default:
			alert('Unknown action');
		break;
	}
}

function DateWidget(name, show_default)
{
	if (!document.getElementById(name))
	{
		alert('Component field missed');
		return;
	}
	this.show_default = show_default;
	this.field_id = name;
	this.control = document.getElementById(name);
	this.day_select = document.getElementById(name+"_day");
	this.month_select = document.getElementById(name+"_month");
	this.year_select = document.getElementById(name+"_year");
	this.value = document.getElementById(name).value;
	this.year = this.value ? this.value.substr(0,4) : 0;
	this.month = this.value ? parseInt(this.value.substr(5,2), 10) : 0;
	this.day = this.value ? this.value.substr(8, 2) : 0;
}

DateWidget.prototype.init = function()
{
	this.remove_options(this.year_select);
	this.remove_options(this.month_select);
	this.remove_options(this.day_select);
	this.setup_options(this.year_select, this.year_options(), this.year);
	this.setup_options(this.month_select, this.month_options(), this.month);
	this.setup_options(this.day_select, this.day_options(this.year, this.month), this.day);
	//var obj = this;
	//this.setValue();
	//setTimeout(function(){obj.setValue();}, 3);
}

DateWidget.prototype.setValue = function()
{
	this.control.value = parseInt(this.year) + "-" + this.zerofill((parseInt(this.month)), 2) + "-" + this.zerofill(parseInt(this.day), 2);
	if (this.month_select.value === '-1' && this.day_select.value === '-1' && this.year_select.value === '-1')
	{
		this.control.value = "";
	}
	this.value = this.control.value;
	if (!this.show_default && !this.value) 
	{
		this.setDefaultValue();
	}
}

DateWidget.prototype.setDefaultValue = function()
{
	this.day = this.day_select.value;
	this.month = this.month_select.value;
	this.year = this.year_select.value;
}

DateWidget.prototype.handle_change = function(field)
{
	switch (field)
	{
		case "month":
			this.month = this.month_select.value;
			this.init();
		break;
		case "day":
			this.day = this.day_select.value;
			this.init();
		break;
		case "year":
			this.year = this.year_select.value;
			this.init();
		break;
	}
}

DateWidget.prototype.year_options = function()
{
	var opts = new Array();
	if (this.show_default)
	{
		if (Calendar._DEFOPT)
		{
			opts[opts.length] = {value: -1, text:Calendar._DEFOPT['year']};
		}
		else
		{
			opts[opts.length] = {value: -1, text: '---'};
		}
	}
	for (var i=2000; i>=1950; i--)
	{
		opts[opts.length] = {value: i, text:i};
	}
	return opts;
}

DateWidget.prototype.month_options = function()
{
	var opts = new Array();
	if (this.show_default)
	{
		if (Calendar._DEFOPT)
		{
			opts[opts.length] = {value: -1, text:Calendar._DEFOPT['month']};
		}
		else
		{
			opts[opts.length] = {value: -1, text: '---'};
		}
	}
	for (var i=0; i<Calendar._MN.length; i++)
	{
		opts[opts.length] = {value: i+1, text: Calendar._MN[i]};
	}
	return opts;
}

DateWidget.prototype.day_options = function(y, m)
{
	m = parseInt(m);
	var maxday;
	var opts = new Array();
	if (this.show_default)
	{
		if (Calendar._DEFOPT)
		{
			opts[opts.length] = {value: -1, text:Calendar._DEFOPT['day']};
		}
		else
		{
			opts[opts.length] = {value: -1, text: '---'};
		}
	}
	switch (m)
	{
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			maxday = 31;
		break;
		case 4:
		case 6:
		case 9:
		case 11:
			maxday = 30;
		break;
		case 2:
			maxday = y%4 == 0 ? 29 : 28;
		break;
		default:
			maxday = 31;
		break;
	}
	
	for (var i=1; i<=maxday; i++)
	{
		opts[opts.length] = {value: i, text: i};
	}
	return opts;
}

DateWidget.prototype.setup_options = function(elem, opts, v)
{
	if (!elem) return;
	var ind = 0;
	for (var i=0; i<opts.length; i++)
	{
		var opt = document.createElement("option");
		opt.value = opts[i].value;
		opt.text = opts[i].text;
		if (opts[i].value == v)
		{
			ind = i;
		}
		elem.options.add(opt);
	}
	var obj = this;
	setTimeout(function(){elem.selectedIndex=ind;obj.setValue();}, 1);
}

DateWidget.prototype.remove_options = function(elem)
{
	if (!elem) return;
	while (elem.options.length)
	{
		elem.remove(0);
	}
}

DateWidget.prototype.zerofill = function(s, n)
{
    s = s + "";
    var l = s.length;
    var out = "";
    for (var i=0; i<n-l; i++)
    {
        out = "0"+out;
    }
    out = out + s + "";
    return out;
}