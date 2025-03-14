!(function($){
	const jq = $.noConflict(true);
	// Добавить максимальное количество файлов
	const maxCountFile = parseInt(window.MAX_COUNT_FILE);
	window.uploadFiles = function(el) {
		let p = jq("#p_uploads"),
			files = [...el.files],
			out = [], str = "";
		if(files.length > maxCountFile) {
			alert(`Нельзя загрузить больше ${maxCountFile} файлов`);
			document.upload.reset();
			return !1;
		}
		for (let a of files){
			const regex = /[^.]+$/;
			let m;
			if ((m = regex.exec(a.name)) !== null) {
				let ex = m[0].toLowerCase();
				if(ex == "xlsx" || ex == "pdf"){
					out.push(a.name);
				}else{
					p.html("");
					alert(`Нельзя загрузить данный тип файла!\n${a.name} - ${a.type}`);
					document.upload.reset();
					return !1;
				}
			}
		}
		p.html(out.join("<br>"));
		return !1;
	}
	
}(jQuery));
