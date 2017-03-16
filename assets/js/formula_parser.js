function compare_value(field, obj1, operand, obj2, alerttext, decimals) {
	value1 = parseFormula('', obj1);
	value2 = parseFormula('', obj2);
	
	//alert(value1+' Vs '+value2);
	
	if(operand == '<') {
		if(value1 < value2) { check = true; } else { check = false; }
	} else if(operand == '>') {
		if(value1 > value2) { check = true; } else { check = false; }
	} else if(operand == '<=') {
		if(value1 <= value2) { check = true; } else { check = false; }
	} else if(operand == '>=') {
		if(value1 >= value2) { check = true; } else { check = false; }
	} else if(operand == '==') {
		if(value1 == value2) { check = true; } else { check = false; }
	}
	
	if(check) {
		//number_format(field, decimals);
	} else {
		alert(alerttext);
		field.value = '0';
		//number_format(field, decimals);
		field.focus();
		field.select();
	}
}

function handleEnter(field, event, unsigned) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		if (field.form != null){
				var i;
				for (i = 0; i < field.form.elements.length; i++) {
					if (field == field.form.elements[i])
						if((i+1) == field.form.elements.length) {
							break;
						} else {
							var  f_next = field.form.elements[i+1]; 
							if(f_next.readOnly == true || f_next.type == 'hidden' || f_next.disabled == true || f_next.style.display == 'none') { 
								for(j=1; (i+j) < field.form.elements.length; j++) {
									var  f_next2 = field.form.elements[i+j]; 
									if(f_next2.readOnly != true && f_next2.type != 'hidden' && f_next2.disabled != true && f_next2.style.display != 'none') {
										f_next2.focus();
										break;
									}
								}
							} else {
								field.form.elements[i+1].focus();
							}
						}
				}
		}
	} else {
		onkeyup_function(field, unsigned);
		//+++++++++++++++++ Check Key Event For Save +++++++++++++
		//parent.topFrame.document.form1.event_check.value='He!';
	}
}

function onkeyup_function(objNumber, unsigned) {
	if(objNumber.value == '') {
		objNumber.value = '';
	}
	if(!(isNumber(objNumber, unsigned))) {
		objNumber.value = objNumber.value.substring(0, objNumber.value.length -1);
		if(objNumber.value == '') {
			objNumber.value = '';
		}
	}
}

function Press_Enter(a,kp){
	if(kp == 'c'){
		if(event.keyCode == 13){
			event.keyCode = 9;
		}
	}else if(kp=='t'){
		event.keyCode = 9;
	}
}

function onfocus_format(objNumber) {
	objNumber.value = objNumber.value.split(",").join("");
	objNumber.select();
}

function isNumber(inputVal, unsigned) {
	oneDecimal = false
	inputStr = inputVal.value.toString()
	for (var i = 0; i < inputStr.length; i++) {
		var oneChar = inputStr.charAt(i);
		if(unsigned == 1) {
			if (i == 0 && oneChar == '-') {
				continue
			}
		}
		if (oneChar == '.' && !oneDecimal) {
			oneDecimal = true
			continue
		}
		if (oneChar < '0' || oneChar > '9') {
			return false
		}
	}
	return true;
}

function trim (str) {
	str = this != window? this : str;
	return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
}

function number_format_keyup(objNumber, decimals) {
	var point = '.';
	var index=0;
	var diff=0;
	var check_point='';
	var number = trim(objNumber.value);
	number = number.split(",").join("")
	for(i=0; i<number.length; i++) {
		if(number.charAt(i) == point) {
				index=i;
				check_point='yes';
		}
	}
	diff=number.length-index;
	if(diff>3&&check_point!=''){
		objNumber.value = number.substring(0, index+3);
	}
}

function number_format_number(objNumber, decimals) {
	var point = '.';
	var type = 'i';
	var number = objNumber;
	var number_zero = '';
	
	
	if(number == '.') {
		alert("1");
	}
	
	number = number.split(",").join("")
	
	for(i=0; i<number.length; i++) {
		if(number.charAt(i) == point) {
			type = 'f';
		}
	}
	//alert(number);
	if(type == 'f') {
		for(i=0; i<number.length; i++) {
			if(number.charAt(i) == 'e') {
				e_number = (number.substring(i+1, number.length)).split(".").join("");
				if(parseFloat(e_number) < 0) {
					this_number = (number.substring(0, i)).split(".").join("");
					e_sign = (this_number == (this_number = Math.abs(parseFloat(this_number))));
					real_number = "0.";
					real_number = (((e_sign)?'':'-') + real_number);
					for(j=1; j<Math.abs(parseFloat(e_number)); j++) {
						real_number += '0';
					}
					real_number += this_number;
					number = real_number;
				}
			}
		}
		decimal = number.split(".");
	}
	
	if(decimals == 0) {
		number = Math.round(parseFloat(number));
	}
	
	sign = (number == (number = Math.abs(number)));
	number = Math.floor(number*100+0.50000000001);
	number = Math.floor(number/100).toString();
	for (var i = 0; i < Math.floor((number.length-(1+i))/3); i++)
		number = number.substring(0,number.length-(4*i+3))+','+number.substring(number.length-(4*i+3));
	number = (((sign)?'':'-') + number);
	
	if(type == 'i' && decimals > 0) {
		number += '.';
		for(j=1; j<=decimals; j++) {
			number += '0';
		}
	} else if(type == 'f' && decimals > 0) {
		if(decimal[1].length == decimals) {
			number += '.'+decimal[1];
		} else if(decimal[1].length < decimals) {
			number += '.'+decimal[1];
			for(j=1; j<=decimals-decimal[1].length; j++) {
				number += '0';
			}
		} else if(decimal[1].length > decimals) {
			//decimal_value = eval(decimal[1]/Math.pow(10, decimal[1].length))+0.00000000001;
			decimal_value = decimal[1].toString();
			number_string = decimal_value.substring(0, (decimals)+1);
			number_eval = parseFloat(number_string)/Math.pow(10, decimals-1);
			number_eval = Math.round(number_eval);
			if(number_eval == Math.pow(10, decimals)) {
				number_eval = 0;
			}
			if(number_eval.toString().length == 1) {
				number_eval = '0'+number_eval.toString();
			}
			if(number_eval.toString().length < decimals) {
				number_zero += '.'+number_eval.toString();
				for(j=1; j<=decimals-number_eval.toString().length; j++) {
					number_zero += '0';
				}
				number += number_zero;
			} else {
				number += '.'+number_eval.toString();
			}
		}
	}
	objNumber.value = number;
}

function number_format(number, decimals) {
	 dec_point  = '.';
	 thousands_sep = ',';
     n = parseFloat(number.value.split(',').join(''));
	 
	 prec = decimals;


		var toFixedFix = function (n,prec) {
        var k = Math.pow(10,prec);
        return (Math.round(n*k)/k).toString();
    };
 
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
 
    var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
 
    var abs = toFixedFix(Math.abs(n), prec);
    var _, i;
 
    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;
 
        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }
 
    var decPos = s.indexOf(dec);
    if (prec >= 1 && decPos !== -1 && (s.length-decPos-1) < prec) {
        s += new Array(prec-(s.length-decPos-1)).join(0)+'0';
    }
    else if (prec >= 1 && decPos === -1) {
        s += dec+new Array(prec).join(0)+'0';
    }
	//alert(s)
	number.value = s;
}

function number_format_num(number, decimals) {

	 dec_point  = '.';
	 thousands_sep = ',';
     n = number
	 prec = decimals;

		var toFixedFix = function (n,prec) {
        var k = Math.pow(10,prec);
        return (Math.round(n*k)/k).toString();
    };
 
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
 
    var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
 
    var abs = toFixedFix(Math.abs(n), prec);
    var _, i;
 
    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;
 
        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }
 
    var decPos = s.indexOf(dec);
    if (prec >= 1 && decPos !== -1 && (s.length-decPos-1) < prec) {
        s += new Array(prec-(s.length-decPos-1)).join(0)+'0';
    }
    else if (prec >= 1 && decPos === -1) {
        s += dec+new Array(prec).join(0)+'0';
    }
	return s;
}


function parseFormula(objTarget, formulaString, decimals) {
	var indentCount = 0;
	
	var indent = function() {
		var s = "|";
		for (var i = 0; i < indentCount; i++) {
			s += "&nbsp;&nbsp;&nbsp;|";
		}  
		return s;
	};

	var formula = formulaString;
	var tokens = getTokens(formula);
	
	var Sum = 0;
	var oper = 0;
	var operatorPrev = '';
	while (tokens.moveNext()) {

		var token = tokens.current();

		if (token.subtype == TOK_SUBTYPE_STOP) 
			indentCount -= ((indentCount > 0) ? 1 : 0);

		//Calculate Sum
		if(token.type=='operand') {
			if(token.subtype=='number') {
				oper = parseFloat(token.value);
			} else if(token.subtype=='range') {
				oper = parseFloat(document.getElementById(token.value).value.split(",").join(""));
			}

if(operatorPrev=='+') {
				Sum = parseFloat(Sum) + parseFloat(oper);
			} else if(operatorPrev=='-') {
				Sum = parseFloat(Sum) - parseFloat(oper);
			} else if(operatorPrev=='*') {
				Sum = parseFloat(Sum) * parseFloat(oper);
			} else if(operatorPrev=='/') {
				Sum = parseFloat(Sum) / parseFloat(oper);
			} else {
				Sum = parseFloat(Sum) + parseFloat(oper);
			}
		} else if(token.type=='operator-infix') {
			operatorPrev = token.value;
		}

		if (token.subtype == TOK_SUBTYPE_START) 
		indentCount += 1;
		
	}
	if(decimals == null) { decimals = 2; }
	if(objTarget != '') {
		document.getElementById(objTarget).value = Sum;
		number_format(document.getElementById(objTarget), decimals);
	} else {
		return Sum;
	}
}

var TOK_TYPE_NOOP      = "noop";
var TOK_TYPE_OPERAND   = "operand";
var TOK_TYPE_FUNCTION  = "function";
var TOK_TYPE_SUBEXPR   = "subexpression";
var TOK_TYPE_ARGUMENT  = "argument";
var TOK_TYPE_OP_PRE    = "operator-prefix";
var TOK_TYPE_OP_IN     = "operator-infix";
var TOK_TYPE_OP_POST   = "operator-postfix";
var TOK_TYPE_WSPACE    = "white-space";
var TOK_TYPE_UNKNOWN   = "unknown"

var TOK_SUBTYPE_START       = "start";
var TOK_SUBTYPE_STOP        = "stop";

var TOK_SUBTYPE_TEXT        = "text";
var TOK_SUBTYPE_NUMBER      = "number";
var TOK_SUBTYPE_LOGICAL     = "logical";
var TOK_SUBTYPE_ERROR       = "error";
var TOK_SUBTYPE_RANGE       = "range";

var TOK_SUBTYPE_MATH        = "math";
var TOK_SUBTYPE_CONCAT      = "concatenate";
var TOK_SUBTYPE_INTERSECT   = "intersect";
var TOK_SUBTYPE_UNION       = "union";


function f_token(value, type, subtype) {
	this.value = value;
	this.type = type;
	this.subtype = subtype;
}

function f_tokens() {

	this.items = new Array();
	
	this.add = function(value, type, subtype) { 
		if (!subtype) subtype = ""; 
		token = new f_token(value, type, subtype); 
		this.addRef(token); return token; 
	};
	this.addRef = function(token) { this.items.push(token); };

	this.index = -1;
	this.reset = function() { this.index = -1; };
	this.BOF = function() { return (this.index <= 0); };
	this.EOF = function() { return (this.index >= (this.items.length - 1)); };
	this.moveNext = function() { if (this.EOF()) return false; this.index++; return true; };
	this.current = function() { if (this.index == -1) return null; return (this.items[this.index]); };
	this.next = function() { if (this.EOF()) return null; return (this.items[this.index + 1]); };
	this.previous = function() { if (this.index < 1) return null; return (this.items[this.index - 1]); };

}

function f_tokenStack() {

	this.items = new Array();
	
	this.push = function(token) { this.items.push(token); };
	this.pop = function() { var token = this.items.pop(); return (new f_token("", token.type, TOK_SUBTYPE_STOP)); };
	
	this.token = function() { return ((this.items.length > 0) ? this.items[this.items.length - 1] : null); };
	this.value = function() { return ((this.token()) ? this.token().value : ""); };
	this.type = function() { return ((this.token()) ? this.token().type : ""); };
	this.subtype = function() { return ((this.token()) ? this.token().subtype : ""); };

}

function getTokens(formula) {

	var tokens = new f_tokens();
	var tokenStack = new f_tokenStack();
	
	var offset = 0;
	
	var currentChar = function() { return formula.substr(offset, 1); };
	var doubleChar  = function() { return formula.substr(offset, 2); };
	var nextChar    = function() { return formula.substr(offset + 1, 1); };
	var EOF         = function() { return (offset >= formula.length); };
	
	var token = "";
	
	var inString = false;
	var inPath = false;
	var inRange = false;
	var inError = false;

	while (formula.length > 0) {
		if (formula.substr(0, 1) == " ") 
			formula = formula.substr(1);
		else {
			if (formula.substr(0, 1) == "=") 
				formula = formula.substr(1);
			break;    
		}
	}

	while (!EOF()) {
	
		if (inString) {    
			if (currentChar() == "\"") {
				if (nextChar() == "\"") {
					token += "\"";
					offset += 1;
				} else {
					inString = false;
					tokens.add(token, TOK_TYPE_OPERAND, TOK_SUBTYPE_TEXT);
					token = "";
				}      
			} else {
				token += currentChar();
			}
			offset += 1;
			continue;    
		} 
		if (inPath) {
			if (currentChar() == "'") {
				if (nextChar() == "'") {
					token += "'";
					offset += 1;
				} else {
					inPath = false;
				}      
			} else {
				token += currentChar();
			}
			offset += 1;
			continue;    
		}    
		if (inRange) {
			if (currentChar() == "]") {
				inRange = false;
			}
			token += currentChar();
			offset += 1;
			continue;
		}
		if (inError) {
			token += currentChar();
			offset += 1;
			if ((",#NULL!,#DIV/0!,#VALUE!,#REF!,#NAME?,#NUM!,#N/A,").indexOf("," + token + ",") != -1) {
				inError = false;
				tokens.add(token, TOK_TYPE_OPERAND, TOK_SUBTYPE_ERROR);
				token = "";
			}
			continue;
		}
		if (currentChar() == "\"") {  
			if (token.length > 0) {
				// not expected
				tokens.add(token, TOK_TYPE_UNKNOWN);
				token = "";
			}
			inString = true;
			offset += 1;
			continue;
		}

		if (currentChar() == "'") {
			if (token.length > 0) {
				// not expected
				tokens.add(token, TOK_TYPE_UNKNOWN);
				token = "";
			}
			inPath = true;
			offset += 1;
			continue;
		}

		if (currentChar() == "[") {
			inRange = true;
			token += currentChar();
			offset += 1;
			continue;
		}

		if (currentChar() == "#") {
			if (token.length > 0) {
				// not expected
				tokens.add(token, TOK_TYPE_UNKNOWN);
				token = "";
			}
			inError = true;
			token += currentChar();
			offset += 1;
			continue;
		}

		// mark start and end of arrays and array rows

		if (currentChar() == "{") {  
			if (token.length > 0) {
				// not expected
				tokens.add(token, TOK_TYPE_UNKNOWN);
				token = "";
			}
			tokenStack.push(tokens.add("ARRAY", TOK_TYPE_FUNCTION, TOK_SUBTYPE_START));
			tokenStack.push(tokens.add("ARRAYROW", TOK_TYPE_FUNCTION, TOK_SUBTYPE_START));
			offset += 1;
			continue;
		}

		if (currentChar() == ";") {  
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.addRef(tokenStack.pop());
			tokens.add(",", TOK_TYPE_ARGUMENT);
			tokenStack.push(tokens.add("ARRAYROW", TOK_TYPE_FUNCTION, TOK_SUBTYPE_START));
			offset += 1;
			continue;
		}

		if (currentChar() == "}") {  
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.addRef(tokenStack.pop());
			tokens.addRef(tokenStack.pop());
			offset += 1;
			continue;
		}

		// trim white-space
		
		if (currentChar() == " ") {
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.add("", TOK_TYPE_WSPACE);
			offset += 1;
			while ((currentChar() == " ") && (!EOF())) { 
				offset += 1; 
			}
			continue;     
		}

		// multi-character comparators
		
		if ((",>=,<=,<>,").indexOf("," + doubleChar() + ",") != -1) {
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.add(doubleChar(), TOK_TYPE_OP_IN, TOK_SUBTYPE_LOGICAL);
			offset += 2;
			continue;     
		}

		// standard infix operators
		
		if (("+-*/^&=><").indexOf(currentChar()) != -1) {
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.add(currentChar(), TOK_TYPE_OP_IN);
			offset += 1;
			continue;     
		}

		// standard postfix operators
		
		if (("%").indexOf(currentChar()) != -1) {
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.add(currentChar(), TOK_TYPE_OP_POST);
			offset += 1;
			continue;     
		}

		// start subexpression or function
		
		if (currentChar() == "(") {
			if (token.length > 0) {
				tokenStack.push(tokens.add(token, TOK_TYPE_FUNCTION, TOK_SUBTYPE_START));
				token = "";
			} else {
				tokenStack.push(tokens.add("", TOK_TYPE_SUBEXPR, TOK_SUBTYPE_START));
			}
			offset += 1;
			continue;
		}

		// function, subexpression, array parameters
		
		if (currentChar() == ",") {
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			if (!(tokenStack.type() == TOK_TYPE_FUNCTION)) {
				tokens.add(currentChar(), TOK_TYPE_OP_IN, TOK_SUBTYPE_UNION);
			} else {
				tokens.add(currentChar(), TOK_TYPE_ARGUMENT);
			}
			offset += 1;
			continue;
		}

		// stop subexpression
		
		if (currentChar() == ")") {
			if (token.length > 0) {
				tokens.add(token, TOK_TYPE_OPERAND);
				token = "";
			}
			tokens.addRef(tokenStack.pop());
			offset += 1;
			continue;
		}

		// token accumulation
		
		token += currentChar();
		offset += 1;

	}

	// dump remaining accumulation
	
	if (token.length > 0) tokens.add(token, TOK_TYPE_OPERAND);
	
	// move all tokens to a new collection, excluding all unnecessary white-space tokens

	var tokens2 = new f_tokens();

	while (tokens.moveNext()) {
	
		token = tokens.current();
		
		if (token.type == TOK_TYPE_WSPACE) {
			if ((tokens.BOF()) || (tokens.EOF())) {}
			else if (!(
				((tokens.previous().type == TOK_TYPE_FUNCTION) && (tokens.previous().subtype == TOK_SUBTYPE_STOP)) || 
				((tokens.previous().type == TOK_TYPE_SUBEXPR) && (tokens.previous().subtype == TOK_SUBTYPE_STOP)) || 
				(tokens.previous().type == TOK_TYPE_OPERAND)
			)) {}
			else if (!(
			((tokens.next().type == TOK_TYPE_FUNCTION) && (tokens.next().subtype == TOK_SUBTYPE_START)) || 
			((tokens.next().type == TOK_TYPE_SUBEXPR) && (tokens.next().subtype == TOK_SUBTYPE_START)) ||
			(tokens.next().type == TOK_TYPE_OPERAND)
			)) {}
			else 
				tokens2.add(token.value, TOK_TYPE_OP_IN, TOK_SUBTYPE_INTERSECT);
			continue;
		}

		tokens2.addRef(token);

	}

	// switch infix "-" operator to prefix when appropriate, switch infix "+" operator to noop when appropriate, identify operand 
	// and infix-operator subtypes, pull "@" from in front of function names
	
	while (tokens2.moveNext()) {
	
		token = tokens2.current();
		
		if ((token.type == TOK_TYPE_OP_IN) && (token.value == "-")) {
			if (tokens2.BOF())
				token.type = TOK_TYPE_OP_PRE;
			else if (
			((tokens2.previous().type == TOK_TYPE_FUNCTION) && (tokens2.previous().subtype == TOK_SUBTYPE_STOP)) || 
			((tokens2.previous().type == TOK_TYPE_SUBEXPR) && (tokens2.previous().subtype == TOK_SUBTYPE_STOP)) || 
			(tokens2.previous().type == TOK_TYPE_OP_POST) || 
			(tokens2.previous().type == TOK_TYPE_OPERAND)
			)
				token.subtype = TOK_SUBTYPE_MATH;
			else
				token.type = TOK_TYPE_OP_PRE;
			continue;
		}

		if ((token.type == TOK_TYPE_OP_IN) && (token.value == "+")) {
			if (tokens2.BOF())
				token.type = TOK_TYPE_NOOP;
			else if (
			((tokens2.previous().type == TOK_TYPE_FUNCTION) && (tokens2.previous().subtype == TOK_SUBTYPE_STOP)) || 
			((tokens2.previous().type == TOK_TYPE_SUBEXPR) && (tokens2.previous().subtype == TOK_SUBTYPE_STOP)) || 
			(tokens2.previous().type == TOK_TYPE_OP_POST) || 
			(tokens2.previous().type == TOK_TYPE_OPERAND)
			)
				token.subtype = TOK_SUBTYPE_MATH;
			else
				token.type = TOK_TYPE_NOOP;
			continue;
		}

		if ((token.type == TOK_TYPE_OP_IN) && (token.subtype.length == 0)) {
			if (("<>=").indexOf(token.value.substr(0, 1)) != -1) 
				token.subtype = TOK_SUBTYPE_LOGICAL;
			else if (token.value == "&")
				token.subtype = TOK_SUBTYPE_CONCAT;
			else
				token.subtype = TOK_SUBTYPE_MATH;
			continue;
		}

		if ((token.type == TOK_TYPE_OPERAND) && (token.subtype.length == 0)) {
			if (isNaN(parseFloat(token.value)))
				if ((token.value == 'TRUE') || (token.value == 'FALSE'))
					token.subtype = TOK_SUBTYPE_LOGICAL;
				else
					token.subtype = TOK_SUBTYPE_RANGE;
			else
				token.subtype = TOK_SUBTYPE_NUMBER;
			continue;
		}

		if (token.type == TOK_TYPE_FUNCTION) {
			if (token.value.substr(0, 1) == "@")
				token.value = token.value.substr(1);
			continue;
		}

	}
	
	tokens2.reset();

	// move all tokens to a new collection, excluding all noops
	
	tokens = new f_tokens();

	while (tokens2.moveNext()) {
		if (tokens2.current().type != TOK_TYPE_NOOP)
			tokens.addRef(tokens2.current());
	}  

	tokens.reset();

	return tokens;
}

function currencyFormat(fld, milSep, decSep, e) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	
	if (whichCode == 13) return true;  // Enter
	
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	
	len = fld.value.length;
	for(i = 0; i < len; i++)
		if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
		
	aux = '';
	for(; i < len; i++)
		if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
		
	aux += key;
	len = aux.length;
	if (len == 0) fld.value = '';
	if (len == 1) fld.value = '0'+ decSep + '0' + aux;
	if (len == 2) fld.value = '0'+ decSep + aux;
	if (len > 2) {
		aux2 = '';
		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += milSep;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}
		fld.value = '';
		len2 = aux2.length;
		
		for (i = len2 - 1; i >= 0; i--)
			fld.value += aux2.charAt(i);
			
		fld.value += decSep + aux.substr(len - 2, len);
	}
	return false;
}


function AddZero(id,amount)
{
	var Zero='';
	var Auto='';
	for(var i=1;i<=amount;i++)
	{
		 Zero+='0';
    }
	Auto=Zero.substr($('#'+id).val().length);
	if($('#'+id).val()!='' && $('#'+id).val()>0)
	{
	   $('#'+id).val(Auto+$('#'+id).val());
	}
}// end function 

function AddZeroTxt(id,amount)
{
	var Zero='';
	var Auto='';
	for(var i=1;i<=amount;i++){
		 Zero+='0';
    }
	Auto=Zero.substr($('#'+id).val().length);
	if($('#'+id).val()!=''){
	   $('#'+id).val(Auto+$('#'+id).val());
	}
}// end function 


function Addformat(objNumber, decimals) 
{
	var point = '.';
	var type = 'i';
	var number = trim($('#'+objNumber).val());
	var number_zero = '';
	
	
	if(number == '.') {
		alert("1");
	}
	
	number = number.split(",").join("")
	
	for(i=0; i<number.length; i++) {
		if(number.charAt(i) == point) {
			type = 'f';
		}
	}
	//alert(number);
	if(type == 'f') {
		for(i=0; i<number.length; i++) {
			if(number.charAt(i) == 'e') {
				e_number = (number.substring(i+1, number.length)).split(".").join("");
				if(parseFloat(e_number) < 0) {
					this_number = (number.substring(0, i)).split(".").join("");
					e_sign = (this_number == (this_number = Math.abs(parseFloat(this_number))));
					real_number = "0.";
					real_number = (((e_sign)?'':'-') + real_number);
					for(j=1; j<Math.abs(parseFloat(e_number)); j++) {
						real_number += '0';
					}
					real_number += this_number;
					number = real_number;
				}
			}
		}
		decimal = number.split(".");
	}
	
	if(decimals == 0) {
		number = Math.round(parseFloat(number));
	}
	
	sign = (number == (number = Math.abs(number)));
	number = Math.floor(number*100+0.50000000001);
	number = Math.floor(number/100).toString();
	for (var i = 0; i < Math.floor((number.length-(1+i))/3); i++)
		number = number.substring(0,number.length-(4*i+3))+','+number.substring(number.length-(4*i+3));
	number = (((sign)?'':'-') + number);
	
	if(type == 'i' && decimals > 0) {
		number += '.';
		for(j=1; j<=decimals; j++) {
			number += '0';
		}
	} else if(type == 'f' && decimals > 0) {
		if(decimal[1].length == decimals) {
			number += '.'+decimal[1];
		} else if(decimal[1].length < decimals) {
			number += '.'+decimal[1];
			for(j=1; j<=decimals-decimal[1].length; j++) {
				number += '0';
			}
		} else if(decimal[1].length > decimals) {
			//decimal_value = eval(decimal[1]/Math.pow(10, decimal[1].length))+0.00000000001;
			decimal_value = decimal[1].toString();
			number_string = decimal_value.substring(0, (decimals)+1);
			number_eval = parseFloat(number_string)/Math.pow(10, decimals-1);
			number_eval = Math.round(number_eval);
			if(number_eval == Math.pow(10, decimals)) {
				number_eval = 0;
			}
			if(number_eval.toString().length == 1) {
				number_eval = '0'+number_eval.toString();
			}
			if(number_eval.toString().length < decimals) {
				number_zero += '.'+number_eval.toString();
				for(j=1; j<=decimals-number_eval.toString().length; j++) {
					number_zero += '0';
				}
				number += number_zero;
			} else {
				number += '.'+number_eval.toString();
			}
		}
	}
	$('#'+objNumber).val(number);
}//end function 

function isNumberKey(){
		if (event.keyCode < 48 || event.keyCode > 57 ){
				if(event.keyCode!=46){
					event.returnValue = false;
				}
         }
}
