YUI.add("moodle-availability_sslcommerz-form",function(s,e){M.availability_sslcommerz=M.availability_sslcommerz||{},M.availability_sslcommerz.form=s.Object(M.core_availability.plugin),M.availability_sslcommerz.form.initInner=function(e){this.currencies=e},M.availability_sslcommerz.form.getNode=function(e){var t,i,a,l,n="";for(t in this.currencies)n+='<option value="'+t+'" '+(e.currency===t?' selected="selected" ':"")+" >",n+=this.currencies[t],n+="</option>";return i="<div><table><tr><td><label>",i+="</label></td><td>",i+='<input name="businessemail" type="hidden" /></td></tr>',i+="<tr><td><label>",i+=M.util.get_string("currency","availability_sslcommerz"),i+="</label></td><td>",i+='<select name="currency" />'+n+"</select></td></tr>",i+="<tr><td><label>",i+=M.util.get_string("cost","availability_sslcommerz"),i+="</label></td><td>",i+='<input name="cost" type="text" /></td></tr>',i+="<tr><td><label>",i+="</label></td><td>",i+='<input name="itemname" type="hidden" /></td></tr>',i+="<tr><td><label>",i+="</label></td><td>",i+='<input name="itemnumber"  type="hidden" /></td></tr></table>',a=s.Node.create("<span>"+i+"</span>"),e.businessemail&&a.one("input[name=businessemail]").set("value",e.businessemail),e.cost&&a.one("input[name=cost]").set("value",e.cost),e.itemname&&a.one("input[name=itemname]").set("value",e.itemname),e.itemnumber&&a.one("input[name=itemnumber]").set("value",e.itemnumber),M.availability_sslcommerz.form.addedEvents||(M.availability_sslcommerz.form.addedEvents=!0,(l=s.one(".availability-field")).delegate("change",function(){M.core_availability.form.update()},".availability_sslcommerz select[name=currency]"),l.delegate("change",function(){M.core_availability.form.update()},".availability_sslcommerz input")),a},M.availability_sslcommerz.form.fillValue=function(e,t){e.businessemail=t.one("input[name=businessemail]").get("value"),e.currency=t.one("select[name=currency]").get("value"),e.cost=this.getValue("cost",t),e.itemname=t.one("input[name=itemname]").get("value"),e.itemnumber=t.one("input[name=itemnumber]").get("value")},M.availability_sslcommerz.form.getValue=function(e,t){var i=t.one("input[name="+e+"]").get("value");return/^[0-9]+([.,][0-9]+)?$/.test(i)?parseFloat(i.replace(",",".")):i},M.availability_sslcommerz.form.fillErrors=function(e,t){var i={};this.fillValue(i,t),(i.cost!==undefined&&"string"==typeof i.cost||i.cost<=0)&&e.push("availability_sslcommerz:error_cost")}},"@VERSION@",{requires:["base","node","event","moodle-core_availability-form"]});