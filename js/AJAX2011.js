/*
* @V1.2
* @ - can get data from specified form to post and get and store in variable
* @ - specified div id to display data
* @ - 
* @merge and modified by
* @Suttapong Wara-asawapati
* @Class thaiajax-Author:vasupat chantakeaw(http://www.thaiajax.com)
* @Class xmlhttp.js-Author
* @		http://www.koders.com/javascript/fidC05D67020D2C88EA133512C37E289A7E18D67040.aspx
* @Class xml2array-Author: (http://www.openjs.com/scripts/xml_parser/)[file:http://ajaxcribbage.com/cribbage/test/xml2array.js]
usage
1. requestData(requestFile,URLString,innerformID,innerDivID,index,methodType,responseType,loadingType,timeout)
2. clearData(index)
3. showData(index,innerDivID)
index = name of data to be store(temp)
requestFile = url(not null)
innerDivID = div id to be display(null)
URLString = GET string[eg. 'name=name&age=25' ](null)
innerformID = form id to get data(null)
methodType = 'GET' or 'POST'(GET)
responseType = 'Text' or 'XML'(Text)
loadingType = 0,1,2(0)
timeout = millisec(0)
*/
var isIE = false;
var ajaxObjects = new Array();
function requestData(requestFile,URLString,innerformID,innerDivID,index,methodType,responseType,loadingType,timeout){
	var requestFile; var index; var innerDivID; var URLString; var innerformID;
	var methodType; var responseType; var loadingType; var timeout;
	if(index == null){index = "temp"}
	ajaxObjects[index] = new ajaxrequest();
	if(requestFile!=''){ajaxObjects[index].requestFile = requestFile;}else{
		alert("requestData error : no request file");return;
	}
	ajaxObjects[index].requestFile=requestFile;
	ajaxObjects[index].URLString=URLString || null;
	if(innerformID!=null){	ajaxObjects[index].formElement = g(innerformID);}
	ajaxObjects[index].innerformID=innerformID;
	if(methodType!=null){methodType = methodType.toUpperCase();}
	ajaxObjects[index].method=methodType || 'GET';
	if(responseType!=null){responseType = responseType.toUpperCase();}
	ajaxObjects[index].responseType=responseType || 'TEXT';
	ajaxObjects[index].loadingType=loadingType || 0;
	ajaxObjects[index].timeout=timeout || 60000;
	ajaxObjects[index].innerDivID=innerDivID;
	ajaxObjects[index].loadXMLDoc();
}
function clearData(index){
	if(ajaxObjects[index]){
		delete ajaxObjects[index];
	}else{
		alert("no data");
	}
}
function showData(index,innerDivID){
	if(ajaxObjects[index]){
		g(innerDivID).innerHTML = ajaxObjects[index].response;
	}else{
		g(innerDivID).innerHTML = '';
		alert("no data");
	}
}
function alertData(index){
	if(ajaxObjects[index]){
		alert(ajaxObjects[index].response);
	}else{
		alert("no data");
	}
}
function g(namex){
	if (document.getElementById){
		return document.getElementById(namex);
	}else if (document.all){
		return document.all[namex];
	}else{
		return null;
	}
}

function ajaxrequest(){
	this.timer;
	this.xmlhttp;
	this.formElement;
	this.requestFile;
	this.URLString;
	this.innerDivID;
	this.innerformID;
	this.timeout;
	this.method 		= 'GET';
	this.responseType 	= 'Text'; /*Text , XML*/
	this.loadingType 	= 0;
	this.async 			= true;
	/* internal status flags */
	this.isAborted = false;
	this.isLoading = false;
	this.isLoaded = false;
	this.isInteractive = false;
	this.isComplete = false;
	/* event handlers (attached functions get called if readyState reached) */
	this.onLoading = null;		// if readyState 1
	this.onLoaded = null;		// if readyState 2
	this.onInteractive = null;	// if readyState 3
	this.onComplete = null;		// if readyState 4
	this.onError = null;		// if readyState 4 and status != 200
	this.onTimeout = null;		// if timeout reached
	this.callback = null;		// if readyState 4 and status == 200
	this.callbackArgs = null;

	this.onCompletion = function(){
		if(this.innerDivID){g(this.innerDivID).innerHTML = this.response;}
	}
	this.ConnXmlHttp = function(){
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				isIE = true;
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
				xmlhttp = false;
			}
		}
		if(!xmlhttp && document.createElement){
			xmlhttp = new XMLHttpRequest();
		}
		return xmlhttp;
	}
	
	this.getRequestBody = function(myForm) {
		var aParams = new Array();  
			for (var i=0 ; i < myForm.elements.length; i++) {
				var formElement = myForm.elements[i];
				if(formElement.type=='checkbox' && !formElement.checked){ continue;} 
				var sParam = encodeURIComponent(myForm.elements[i].name);
			   	sParam += "=";
			   	sParam += encodeURIComponent(myForm.elements[i].value);
			   	aParams.push(sParam);
		   	}	 
		   	return aParams.join("&");		
	}
	
	this.loadXMLDoc = function() {
		this.xmlhttp = this.ConnXmlHttp();
		var self = this;
		var req = new XMLHttpRequest();
		if (this.method == "GET") {
			if(this.responseType!='XML'){
				if(this.URLString==null && this.formElement!= null){
					this.URLString = this.getRequestBody(this.formElement);
				}
			}
			this.xmlhttp.open(this.method, this.requestFile+'?'+this.URLString, this.async);
			req.open(this.method, this.requestFile+'?'+this.URLString, this.async);
		}else if (this.method == "POST"){
			if(this.formElement!= null){this.URLString = this.getRequestBody(this.formElement);}
			this.xmlhttp.open(this.method,this.requestFile, this.async);
  			try {
				this.xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded')  
			} catch (e) {}
			req.open(this.method, this.requestFile, this.async);
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		}
		/* these work only on IE
		this.xmlhttp.onreadystatechange = function() {
			if (this.xmlhttp == null) { return; }
			if (this.xmlhttp.readyState == 1) { self._doLoading(self); }
			if (this.xmlhttp.readyState == 2) { self._doLoaded(self); }
			if (this.xmlhttp.readyState == 3) { self._doInteractive(self); }
			if (this.xmlhttp.readyState == 4) { self._doComplete(self); }
			self.onCompletion();
			//alert(self.status+' '+self.statusText);
		}/**/
		req.onreadystatechange = function (aEvt) {
			if (req == null) { return; }
			if (req.readyState == 1) { self._doLoading(self,req); }
			if (req.readyState == 2) { self._doLoaded(self,req); }
			if (req.readyState == 3) { self._doInteractive(self,req); }
			if (req.readyState == 4) { self._doComplete(self,req); }
			self.onCompletion();
		};
		if (self.timeout > 0) {
			var that = this;
			var f = function() {
				that._doTimeout(that,req);
				self.loading(0);
				self.onCompletion();
			}
			timer=setTimeout(f,this.timeout);
		}
		this.xmlhttp.send(this.URLString);
		req.send(this.URLString);
	}
	/** Show Loading	**/
	this.loading = function(v){
		var v;
		if(this.loadingType==2){
			if(!g("loadingDiv")) {this.createLoadingDiv();}
			g("loadingDiv").style.visibility = "visible";
			if(v==1){
				if(this.loadingType==2){ g("loadingDiv").style.visibility = "visible";}
			}else{
				if(this.loadingType==2){ g("loadingDiv").style.visibility = "hidden";}
			}
		}
	}

	this.createLoadingDiv = function(){
		var connLoading = document.createElement("DIV")
			connLoading.id = "loadingDiv";
			connLoading.style.position="absolute";
			connLoading.style.left="0px";
			connLoading.style.top="0px";
			connLoading.style.width="100%";
			connLoading.style.height="100%";
			connLoading.style.verticalAlign="middle"
			connLoading.style.zIndex=9999;
			connLoading.style.backgroundColor="#FFFFFF";
			connLoading.style.visibility = "hidden";
			connLoading.style.font = '11px Lucida Sans Unicode';
			connLoading.style.filter = 'alpha(opacity=60)';
			connLoading.innerHTML = "<table width='100%' height='100%'><tr><td align='center' valign='middle'><IMG SRC='loading2.gif'></td></tr></table>";
		document.body.appendChild(connLoading);
	}
///////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------
// built in helper methods
//----------------------------------------------------------------------------

/** @private */
	this._doLoading = function(self,req) {
		if (self.isLoading) { return; }
		if (typeof(self.onLoading)=="function") {
			self.onLoading(req);
			}
		self.isLoading = true;
		self.loading(1);
		if(self.loadingType==2){
			self.response = "Loading...";
		}else if(self.loadingType==1){
			self.response = "Loading";
		}else{
			self.response = "";
		}/**/
	}

/** @private */
	this._doLoaded = function(self,req) {
		if (self.isLoaded) { return; }
		if (typeof(self.onLoaded)=="function") {
			self.onLoaded(req);
		}
		self.isLoaded = true;
		self.loading(0);
		self.response = "_doLoaded";
	}

/** @private */
	this._doInteractive = function(self,req) {
		if (self.isInteractive) { return; }
		if (typeof(self.onInteractive)=="function") {
			self.onInteractive(req);
		}
		self.isInteractive = true;
		self.loading(0);
		self.response = "_doInteractive";
	}

/** @private */
	this._doComplete = function(self,req) {
		if (req==null || self.isComplete || self.isAborted) { return; }
		self.isComplete = true;
		try {
			self.status = req.status;
			self.statusText = req.statusText;
			self.responseText = req.responseText;
			self.responseXML = req.responseXML;
		} catch(e) {
			// cannot connect
			self.status = null;
			self.statusText = null;
			self.responseText = null;
			self.responseXML = null;
		}
		if (self.status!=null && typeof(self.onComplete)=="function") {
			self.onComplete(self.xmlhttp);
		}
		if (self.status==200 && typeof(self.callback)=="function") {
			if (self.callbackArgs) {
				self.callback(self.xmlhttp, self.callbackArgs);
			}
			else {
				self.callback(self.xmlhttp);
			}
		}
		if (self.status!=200 && typeof(self.onError)=="function") {
			self.onError(req);
		}
		if (self.async) {
			// on async calls, clean up so IE does not leak memory
			delete req['onreadystatechange'];
			req = null;
		}
		if(self.responseType=='XML'){
			self.response =self.responseXML;
			var arr2 = xml2array(self.response);
		}else{
			self.response = self.responseText;
		}
		clearTimeout(timer);
	}

/** @private */
	this._doTimeout = function(self,req) {
		if (req!=null && !self.isComplete) {
			self.isAborted = true;  // skip call of onError from abort
			req.abort();
			if (typeof(self.onTimeout)=="function") {
				self.onTimeout(req);
			}
			if (self.xmlhttp!=null) {
				// Opera will not fire onreadystatechange after abort, but other browsers do. 
				// So we cannot rely on the onreadystate function getting called. Clean up here!
				delete req['onreadystatechange'];
				req = null;
			}
			self.response = "_doTimeout";
		}
	}
}//end of ajaxrequest()
