function hide_option(){
        $("#cd option:gt(0)").hide()
    }
    function validTel(a){
        var b=/^[0-9]{11}$/;
        if(b.test(a)){
            return true
        }
        return false
    }
    $(function(){
        $.getJSON("http://csxyxzs.sinaapp.com/tool/cooperation_dk.php",function(a){
            $.each(a.items,function(b,d){
                var c="<option "+"sid="+d.id+">"+d.name+"</option>";
                $("#dk").append(c);
                $.each(d.caidan,function(e,g){
                    var f="<option "+"sid="+d.id+">"+g.name+" "+g.price+"</option>";
                    $("#cd").append(f)
                })
            });
            hide_option()
        });
        $("#dk").change(function(){
            if($("#dk :selected").attr("sid")){
                hide_option();
                $("#cd option[sid="+$("#dk :selected").attr("sid")+"]").show()
            else{
                //FIX
                hide_option()
                }
            }	
        });
        $("#submit").click(function(){
            if($("#dk option:selected[df!=1]").length>0&&$("#cd option:selected[df!=1]").length>0&&$("#dz option:selected[df!=1]").length>0){
                if(validTel($("#tel").val())){
                    var c=$("#tel").val();
                    var d=$("#dk :selected").attr("sid");
                    var f=$("#cd :selected").text();
                    var b=$("#dz :selected").text();
                    var a=$("#extra").val();
                    console.log($("#tel").val());
                    console.log($("#dk :selected").attr("sid"));
                    console.log($("#cd :selected").text());
                    console.log($("#dz :selected").text());
                    console.log($("#extra").val());
                    var e={
                        dk:d,cd:f,dz:b,tel:c,extra:a
                    };
                    $.post("http://csxyxzs.sinaapp.com/tool/cooperation_dk_rec.php",e,function(h,i,g){
                        console.log(h);
                        if(h="订单已告诉商家，请等待。"){
                            $("#submit").hide();
                            $("#reply").html("订单已完成，请等待商家确认。")
                        }
                    })
                }
            }
            return false
        })
	});