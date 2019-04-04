function multiUploader(config){
  
	this.config = config;
	this.items = "";
	this.all = []
	var self = this;
	
	multiUploader.prototype._init = function(){
		if (window.File && 
			window.FileReader && 
			window.FileList && 
			window.Blob) {		
			 var inputId = $("#"+this.config.form).find("input[type='file']").eq(0).attr("id");
			 document.getElementById(inputId).addEventListener("change", this._read, false);
			 document.getElementById(this.config.dragArea).addEventListener("dragover", function(e){ e.stopPropagation(); e.preventDefault(); }, false);
			 document.getElementById(this.config.dragArea).addEventListener("drop", this._dropFiles, false);
			 document.getElementById(this.config.form).addEventListener("submit", this._submit, false);			 	 
		} else
			console.log("Browser supports failed");
	}
	
	multiUploader.prototype._submit = function(e){
		e.stopPropagation(); e.preventDefault();
		self._startUpload();
	}
	
	multiUploader.prototype._preview = function(data){
		this.items = data;
		if(this.items.length > 0){
			var html = "";		
			var uId = "";
 			for(var i = 0; i<this.items.length; i++){
				uId = this.items[i].name._unique();
				var sampleIcon = '<img src="'+ bkfolder +'images/image.png" />';
				var errorClass = "";				
				if(typeof this.items[i] != undefined){
					if(self._validate(this.items[i].type) <= 0) {
						sampleIcon = '<img src="images/unknown.png" />';
						errorClass =" invalid";
					} 
					html += '<div class="dfiles'+errorClass+'" rel="'+uId+'"><h5>'+sampleIcon+this.items[i].name+'</h5><div id="'+uId+'" class="progress" style="display:none;"><img src="'+ bkfolder +'images/ajax-loader.gif" /></div></div>';
				}
			}
			$("#dragAndDropFiles").append(html);
		}
	}

	multiUploader.prototype._read = function(evt){
		if(evt.target.files){
			self._preview(evt.target.files);
			self.all.push(evt.target.files);
		} else 
			console.log("Failed file reading");
	}
	
	multiUploader.prototype._validate = function(format){			
		var arr = this.config.support.split(",");
		return arr.indexOf(format);
	}
	
	multiUploader.prototype._dropFiles = function(e){
		e.stopPropagation(); e.preventDefault();
		self._preview(e.dataTransfer.files);
		self.all.push(e.dataTransfer.files);
	}
	
	multiUploader.prototype._uploader = function(file,f){
		if(typeof file[f] != undefined && self._validate(file[f].type) > 0){
			var data = new FormData();
			var ids = file[f].name._unique();
			data.append('file',file[f]);
			data.append('index',ids);

            var ms = $('#ms').val();
            data.append('ms',ms);

			if ($("#postdata1").length > 0){
				var postdata1 = $('#postdata1').val();
				data.append('postdata1',postdata1);
			}
			
			if ($("#make_id").length > 0){
				var make_id = $('#make_id').val();
				data.append('make_id',make_id);
			}
			
			if ($("#photocategoryid").length > 0){
				var photocategoryid = $('#photocategoryid').val();
				data.append('photocategoryid',photocategoryid);
			}
			
			if ($("#crop_option").length > 0){
				if($("#crop_option").is(':checked')){
					var crop_option = 1;
				}else{
					var crop_option = 0;
				}
				data.append('crop_option',crop_option);
			}
			
			var rotate_i = $(':radio[name=rotateimage]').length;
			if (rotate_i > 0){
				var rotateimage = $('input[name=rotateimage]:radio:checked').val();
				data.append('rotateimage',rotateimage);
			}
			

			$(".dfiles[rel='"+ids+"']").find(".progress").show();
			$.ajax({
				type:"POST",
				url:this.config.uploadUrl,
				data:data,
				cache: false,
				contentType: false,
				processData: false,
				success:function(rponse){
					$("#"+ids).hide();
					var obj = $(".dfiles").get();
					$.each(obj,function(k,fle){
						if($(fle).attr("rel") == rponse){
							$(fle).slideUp("normal", function(){ $(this).remove(); });
						}
					});
					if (f+1 < file.length) {
						self._uploader(file,f+1);
					} 
					
					callafter();                   
				}
			});
		} else
			console.log("Invalid file format - "+file[f].name);
	}
	
	multiUploader.prototype._startUpload = function(){
		if(this.all.length > 0){
			for(var k=0; k<this.all.length; k++){
				var file = this.all[k];
				this._uploader(file,0);
			}
			this.all = [];			
		}
	}
	
	String.prototype._unique = function(){
		return this.replace(/[a-zA-Z]/g, function(c){
     	   return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    	});
	}

	this._init();
}

function initMultiUploader(){
	new multiUploader(config);
}