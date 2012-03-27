<html>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
<body>
<script type="text/javascript">
$(document).ready();

function Post(){
		$.ajax({
			url: "http://illution.dk/ClickThis/api/answer/",
			type:"POST",
			data : {
    "Answer": [
        {
            "Options": [
                {
                    "id": 2,
                    "value": 1
                }
            ],
            "QuestionId": 10,
            "UserId": 1
        }
    ]
},
			success: function(data){
				/*if(typeof data.error_code == "undefined"){
						data = data.replace("{","{\n");
						data = data.replace("}","\n}\n");
					$("#return").append(data+"\n");
				} else {
					$("#return").append(data+"\n");
				}*/
				$("#return").append(data+"\n");
			},
			error: function(){
				$("#return").append(data+"\n");
			}
		});
}

function Get(){
	$.ajax({
		url: "http://illution.dk/ClickThis/api/series_test/21",
		type:"GET",
		success: function(data){
			if(typeof data.error_code == "undefined"){
				$("#return").append(data.Id+"\n");
			} else {
				$("#return").append(data+"\n");
			}
		},
		error: function(){
			$("#return").append(data+"\n");
		}
	});
}
function Delete(){
	$.ajax({
		url: "http://illution.dk/ClickThis/api/series_test/30",
		type:"DELETE",
		success: function(data){
			console.log("Success");
			if(typeof data.error_code == "undefined"){
				$("#return").append(data+"\n");
			} else {
				$("#return").append(data+"\n");
			}
		},
		error: function(){
			$("#return").append(data+"\n");
		}
	});
}
function Put(){
	$.ajax({
		url: "http://illution.dk/ClickThis/api/series_test/3/",
		type:"PUT",
		data: '{"TargetPeople":["1","2"],"StartText":"Llama","Creator": "Bo"}',
		success: function(data){
			if(typeof data.error_code == "undefined"){
				$("#return").append(data+"\n");
			} else {
				$("#return").append(data+"\n");
			}
		},
		error: function(){
			$("#return").append(data+"\n");
		}
	});
}
</script>
<button onclick="Post();">Post</button>
<button onclick="Get()">Get</button>
<button onclick="Delete()">Delete</button>
<button onclick="Put()">Put</button><br>
<textarea id="return" style="width:90%;height:90%;"></textarea>
</body>
</html>