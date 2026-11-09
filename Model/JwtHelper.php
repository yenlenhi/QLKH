<?php
class JwtHelper {
    private static function secretKey() {
        require_once __DIR__ . '/AppConfig.php';
        return requireAppEnv('JWT_SECRET');
    }

    // Tạo JWT Token
    public static function generate_jwt($payload, $exp_seconds = 86400) { // Mặc định hết hạn sau 24h
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        // Thêm thời gian hết hạn (exp) và thời gian tạo (iat)
        $payload['iat'] = time();
        $payload['exp'] = time() + $exp_seconds;
        $payload = json_encode($payload);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::secretKey(), true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // Kiểm tra & Giải mã JWT Token
    public static function validate_jwt($jwt) {
        if (empty($jwt)) return false;

        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) return false;

        $header = self::base64UrlDecode($tokenParts[0]);
        $payload = self::base64UrlDecode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // Kiểm tra chữ ký
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::secretKey(), true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        if ($base64UrlSignature !== $signatureProvided) {
            return false; // Token đã bị chỉnh sửa/can thiệp
        }

        $data = json_decode($payload, true);
        if (isset($data['exp']) && ($data['exp'] - time()) < 0) {
            return false; // Token đã hết hạn
        }

        return $data; // Trả về thông tin mã hóa trong token nếu hợp lệ
    }

    private static function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode($data) {
        $b64 = str_replace(['-', '_'], ['+', '/'], $data);
        return base64_decode($b64);
    }
}
?>
