function subShareOption(rowIndex){
			$("#shareOption"+rowIndex).remove();
			rowCount--;
};
var rowCount = 1;
$("#firstAdd").click(function(){
	rowCount++;				
	var html = '<div id="shareOption'+rowCount+'">名称：<input type="text" class="input_text"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;价值：<input type="text" class="input_text"/>&nbsp;元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;兑换积分：<input type="text" class="input_text"/>&nbsp;&nbsp;&nbsp;<a href="javascript:subShareOption('+rowCount+');" class="subAndAdd">-</a></div>';
	var tr = $(this).parent();
	tr.append(html);
});
