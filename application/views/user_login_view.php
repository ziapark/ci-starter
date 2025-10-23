<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <?php echo $css_optimizer; ?>
</head>
<body>
    <div class="login-container">
        <h2>로그인</h2>
        <form action="/user/login" method="post">
            <div class="form-group">
                <label for="u_id">아이디</label>
                <input type="text" id="u_id" name="u_id" placeholder="아이디를 입력하세요">
            </div>
            <div class="form-group">
                <label for="u_pw">비밀번호</label>
                <input type="password" id="u_pw" name="u_pw" placeholder="비밀번호를 입력하세요">
            </div>
            <button type="submit" class="login-button">로그인</button>
        </form>
        <div class="extra-links">
            <a href="/user/view/sign">회원가입 |</a>
            <a href="/board/board_list">목록보기</a>
        </div>
    </div>
</body>
</html>