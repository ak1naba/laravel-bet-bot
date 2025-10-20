<?php

namespace App\Http\Telegram;

use Illuminate\Support\Facades\Cache;

class FormWizard extends CommandHandler
{
    private $formSteps = [
        'start' => 'askName',
        'askName' => 'askEmail',
        'askEmail' => 'askAge',
        'askAge' => 'finish'
    ];
    
    public function handle($text = null)
    {
        $currentStep = $this->getCurrentStep();
        
        if ($currentStep === 'start' || !$text) {
            $this->startForm();
        } else {
            $this->processStep($currentStep, $text);
        }
    }
    
    private function getCurrentStep()
    {
        return Cache::get("form_step_{$this->chatId}", 'start');
    }
    
    private function setCurrentStep($step)
    {
        Cache::put("form_step_{$this->chatId}", $step, now()->addHours(2));
    }
    
    private function saveFormData($data)
    {
        Cache::put("form_data_{$this->chatId}", $data, now()->addHours(2));
    }
    
    private function getFormData()
    {
        return Cache::get("form_data_{$this->chatId}", []);
    }
    
    private function startForm()
    {
        $this->setCurrentStep('askName');
        $this->sendMessage("📝 Давайте заполним форму!\n\nКак вас зовут?");
    }
    
    private function processStep($currentStep, $text)
    {
        $formData = $this->getFormData();
        
        switch ($currentStep) {
            case 'askName':
                $formData['name'] = $text;
                $this->saveFormData($formData);
                $this->setCurrentStep('askEmail');
                $this->sendMessage("📧 Отлично! Теперь введите ваш email:");
                break;
                
            case 'askEmail':
                if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
                    $this->sendMessage("❌ Неверный формат email. Попробуйте еще раз:");
                    return;
                }
                $formData['email'] = $text;
                $this->saveFormData($formData);
                $this->setCurrentStep('askAge');
                $this->sendMessage("🎂 Сколько вам лет?");
                break;
                
            case 'askAge':
                if (!is_numeric($text) || $text < 1 || $text > 120) {
                    $this->sendMessage("❌ Пожалуйста, введите корректный возраст:");
                    return;
                }
                $formData['age'] = $text;
                $this->saveFormData($formData);
                $this->finishForm($formData);
                break;
        }
    }
    
    private function finishForm($formData)
    {
        $message = "✅ Форма успешно заполнена!\n\n";
        $message .= "📋 Ваши данные:\n";
        $message .= "👤 Имя: {$formData['name']}\n";
        $message .= "📧 Email: {$formData['email']}\n";
        $message .= "🎂 Возраст: {$formData['age']}";
        
        $this->sendMessage($message);
        
        // Очищаем данные формы
        Cache::forget("form_step_{$this->chatId}");
        Cache::forget("form_data_{$this->chatId}");
        
        // Здесь можно сохранить данные в базу
        // $this->saveToDatabase($formData);
    }
}