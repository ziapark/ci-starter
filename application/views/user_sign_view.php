<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>회원가입</title>
    <?php echo $css_optimizer; ?>
</head>
<body>
    <script>
        let isIdAvailable = false;
        
        function checkDuplicate(){
            const u_id = document.getElementById('u_id').value;
            const resultDiv = document.getElementById('id-check-result');

            if(!u_id){
                alert('아이디를 입력하세요.');
                return;
            }

            $.ajax({
                url: '/user/check_id',
                type: 'POST',
                dataType: 'json',
                data: { u_id: u_id },
                success: function(data){
                    if(data.exists){
                        resultDiv.style.color = 'red';
                        resultDiv.textContent = '이미 존재하는 아이디입니다.';
                        isIdAvailable = false;                       
                    }else{
                        resultDiv.style.color = 'green';
                        resultDiv.textContent = '사용 가능한 아이디입니다.';
                        isIdAvailable = true;                     
                    }
                }
            });
        }

        function validateForm(event) {
            if (!isIdAvailable) {
                event.preventDefault();
                alert('아이디 중복 확인을 완료하고 사용 가능한 아이디를 입력하세요.');
            }
        }

    </script>
    <div class="signup-container">
        <h2>회원가입</h2>
        <form action="/user/sign" method="post" onsubmit="validateForm(event)">
            <div class="form-group">
                <label for="u_name">이름</label>
                <input type="text" id="u_name" name="u_name" placeholder="이름을 입력하세요" required>
            </div>
            <div class="form-group">
                <label for="u_id">아이디</label>
                <input type="text" id="u_id" name="u_id" placeholder="아이디를 입력하세요" required>
                <button type="button" class="check-button" onclick="checkDuplicate()">중복 확인</button>
                <span id="id-check-result" style="margin-top: 8px; font-size: 14px;"></span>
            </div>
            <div class="form-group">
                <label for="u_pw">비밀번호</label>
                <input type="password" id="u_pw" name="u_pw" placeholder="비밀번호를 입력하세요" required>
            </div>
            <button type="submit" class="signup-button">회원가입</button>
        </form>
        <div class="extra-links">
            <a href="/user/view/login">로그인 |</a>
            <a href="/board/board_list">목록보기</a>
        </div>
    </div>
</body>
</html>