<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <style>
        body {display: flex;justify-content: center;height: 100vh;margin: 0;}
        .login-container {padding: 40px 30px;width: 100%;max-width: 400px;}
        .login-container h2 {text-align: center;margin-bottom: 30px;color: #333;}
        .form-group {margin-bottom: 20px;}
        .form-group label {display: block;margin-bottom: 8px;font-weight: bold;color: #555;}
        .form-group input {width: 94%;padding: 10px 12px;font-size: 14px;border: 1px solid #ccc;border-radius: 6px;}
        .login-button {width: 100%;padding: 12px;background-color: #007BFF;border: none;color: white;font-size: 16px;border-radius: 6px;cursor: pointer;}
        .login-button:hover {background-color: #0056b3;}
        .extra-links {text-align: center;margin-top: 15px;}
        .extra-links a {color: #6c6f74ff;text-decoration: none;font-size: 14px;}
    </style>
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