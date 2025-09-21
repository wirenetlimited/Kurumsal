@component('mail::message')
# 🧪 SMTP Test E-postası

**Bu bir test e-postasıdır.**

## 📋 Test Detayları

**Konu:** {{ $subject }}  
**Gönderim Zamanı:** {{ $timestamp }}  
**SMTP Durumu:** ✅ Başarılı

## 💬 Test Mesajı

{{ $message }}

## 🔧 SMTP Yapılandırması

Bu e-posta, SMTP ayarlarınızın doğru çalıştığını gösterir. Eğer bu e-postayı alıyorsanız, e-posta sistemi başarıyla yapılandırılmış demektir.

### ✅ Başarılı Ayarlar:
- **SMTP Sunucu:** {{ config('mail.mailers.smtp.host') }}
- **Port:** {{ config('mail.mailers.smtp.port') }}
- **Şifreleme:** {{ config('mail.mailers.smtp.encryption') }}
- **Gönderen:** {{ config('mail.from.address') }}

## 🏢 Şirket Bilgileri

**{{ $companyInfo['name'] }}**  
{{ $companyInfo['address'] }}  
{{ $companyInfo['email'] }} | {{ $companyInfo['phone'] }}

---

**Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayınız.**

@component('mail::subcopy')
Test e-postası - SMTP Yapılandırması Doğrulaması
@endcomponent
@endcomponent
