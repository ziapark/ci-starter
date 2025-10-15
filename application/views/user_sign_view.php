<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <style>
        body {display: flex;justify-content: center;height: 100vh;margin: 0;}
        .signup-container {background-color: white;padding: 40px 30px;border-radius: 10px;width: 100%;max-width: 450px;}
        .signup-container h2 {text-align: center;margin-bottom: 30px;color: #333;}
        .form-group {margin-bottom: 20px;} 
        .form-group label {display: block;margin-bottom: 8px;font-weight: bold;color: #555;}
        .form-group input {width: 94%;padding: 10px 12px;font-size: 14px;border: 1px solid #ccc;border-radius: 6px;}
        .signup-button {width: 100%;padding: 12px;background-color: #28a745;border: none;color: white;font-size: 16px;border-radius: 6px;cursor: pointer;}
        .signup-button:hover {background-color: #218838;}
        .extra-links {text-align: center;margin-top: 15px;}
        .extra-links a {color: #6c6f74ff;text-decoration: none;font-size: 14px;}
    
    
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>회원가입</h2>
        <form action="../sign" method="post">
            <div class="form-group">
                <label for="u_name">이름</label>
                <input type="text" id="u_name" name="u_name" placeholder="이름을 입력하세요" required>
            </div>
            <div class="form-group">
                <label for="u_id">아이디</label>
                <input type="text" id="u_id" name="u_id" placeholder="아이디를 입력하세요" required>
            </div>
            <div class="form-group">
                <label for="u_pw">비밀번호</label>
                <input type="password" id="u_pw" name="u_pw" placeholder="비밀번호를 입력하세요" required>
            </div>
            <button type="submit" class="signup-button">회원가입</button>
        </form>
        <div class="extra-links">
            <a href="/user/view/login">로그인 |</a>
            <a href="/board/view/board_list">목록보기</a>
        </div>
    </div>
</body>
</html>