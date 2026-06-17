<?php
/**
 * SOUK.IQ Core Input Validator Class
 */

namespace Core;

class Validator {
    protected $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $rule) {
                // Parse rule arguments (e.g. min:8)
                $ruleName = $rule;
                $ruleArg = null;
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $ruleArg) = explode(':', $rule);
                }

                $this->applyRule($field, $value, $ruleName, $ruleArg, $data);
            }
        }

        return empty($this->errors);
    }

    protected function applyRule($field, $value, $rule, $arg, $allData) {
        $value = trim($value);

        switch ($rule) {
            case 'required':
                if ($value === '' || $value === null) {
                    $this->addError($field, 'required', 'هذا الحقل مطلوب.');
                }
                break;
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'email', 'البريد الإلكتروني المدخل غير صالح.');
                }
                break;
            case 'username':
                if (!empty($value) && !preg_match('/^[a-zA-Z0-9_]{3,30}$/', $value)) {
                    $this->addError($field, 'username', 'اسم المستخدم يجب أن يكون بين 3 إلى 30 حرفاً ويحتوي فقط على أحرف، أرقام، أو شرطة سفلية.');
                }
                break;
            case 'min':
                if (!empty($value) && strlen($value) < (int)$arg) {
                    $this->addError($field, 'min', "يجب ألا يقل هذا الحقل عن {$arg} أحرف.");
                }
                break;
            case 'max':
                if (!empty($value) && strlen($value) > (int)$arg) {
                    $this->addError($field, 'max', "يجب ألا يزيد هذا الحقل عن {$arg} حرفاً.");
                }
                break;
            case 'matches':
                $matchValue = $allData[$arg] ?? '';
                if ($value !== $matchValue) {
                    $this->addError($field, 'matches', 'تأكيد الحقل غير متطابق.');
                }
                break;
            case 'phone':
                // Iraqi phone formats: +9647XXXXXXXX or 07XXXXXXXX
                if (!empty($value) && !preg_match('/^(\+964|0)?7[3-9]\d{8}$/', $value)) {
                    $this->addError($field, 'phone', 'رقم الهاتف العراقي المدخل غير صالح (مثال: 07701234567).');
                }
                break;
            case 'password_strength':
                if (!empty($value)) {
                    $hasUpper = preg_match('/[A-Z]/', $value);
                    $hasLower = preg_match('/[a-z]/', $value);
                    $hasDigit = preg_match('/\d/', $value);
                    if (!$hasUpper || !$hasLower || !$hasDigit || strlen($value) < 8) {
                        $this->addError($field, 'password_strength', 'كلمة المرور ضعيفة. يجب أن تحتوي على 8 أحرف على الأقل، تشمل حرفاً كبيراً، حرفاً صغيراً، ورقماً.');
                    }
                }
                break;
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'numeric', 'يجب أن تكون القيمة رقمية.');
                }
                break;
        }
    }

    protected function addError($field, $rule, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
