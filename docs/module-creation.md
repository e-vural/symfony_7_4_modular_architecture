# Modül Klasör Yapısı Oluşturma Komutu

Bu proje, Symfony 7.4 için modüler bir mimari sağlar. Yeni modüller için klasör yapısı oluşturmak üzere özel bir console komutu bulunmaktadır.

## Komut Kullanımı

```bash
php bin/console app:create-module
```

Komut çalıştırıldığında, modül adını girmeniz istenecektir.

## Örnek Kullanım

```bash
$ php bin/console app:create-module
Modül adını girin (örn: Siparis, Urun, Musteri): Urun

Modül klasör yapısı oluşturuluyor: Urun
----------------------------------------

 [OK] Modül 'Urun' klasör yapısı başarıyla oluşturuldu!

      Oluşturulan klasörler:

      - src/Urun/Controller/
      - src/Urun/Entity/
      - src/Urun/Repository/
      - src/Urun/Service/
      - src/Urun/Form/
      - src/Urun/Infrastructure/

 [NOTE] Şimdi Symfony'nin kendi komutlarını kullanarak dosyaları oluşturabilirsiniz:

       Entity oluşturmak için:
         php bin/console make:entity Urun

       Controller oluşturmak için:
         php bin/console make:controller UrunController

       Form oluşturmak için:
         php bin/console make:form UrunForm Urun

       Repository oluşturmak için:
         php bin/console make:repository Urun

       CRUD oluşturmak için:
         php bin/console make:crud Urun

       Migration oluşturmak için:
         php bin/console make:migration

       Migration'ı çalıştırmak için:
         php bin/console doctrine:migrations:migrate
```

## Oluşturulan Klasör Yapısı

Her modül için aşağıdaki klasör yapısı otomatik olarak oluşturulur:

```
src/
└── [ModulAdi]/
    ├── Controller/
    ├── Entity/
    ├── Repository/
    ├── Service/
    ├── Form/
    └── Infrastructure/
```

## Symfony Komutları ile Dosya Oluşturma

Klasör yapısı oluşturulduktan sonra, Symfony'nin kendi komutlarını kullanarak dosyaları oluşturabilirsiniz:

### 1. Entity Oluşturma
```bash
php bin/console make:entity [ModulAdi]
```
- Doctrine entity sınıfı oluşturur
- Alanları interaktif olarak tanımlayabilirsiniz
- Validation kuralları ekleyebilirsiniz

### 2. Controller Oluşturma
```bash
php bin/console make:controller [ModulAdi]Controller
```
- Controller sınıfı oluşturur
- Temel route yapısı sağlar
- Template dosyaları oluşturur

### 3. Form Oluşturma
```bash
php bin/console make:form [ModulAdi]Form [ModulAdi]
```
- Form sınıfı oluşturur
- Entity'ye bağlı form alanları oluşturur
- Validation kuralları ekler

### 4. Repository Oluşturma
```bash
php bin/console make:repository [ModulAdi]
```
- Repository sınıfı oluşturur
- Temel CRUD metodları sağlar
- Özel sorgular ekleyebilirsiniz

### 5. CRUD Oluşturma (Hızlı Başlangıç)
```bash
php bin/console make:crud [ModulAdi]
```
- Entity, Controller, Form ve Repository'yi tek seferde oluşturur
- Template dosyalarını oluşturur
- Route'ları tanımlar

### 6. Migration Oluşturma
```bash
php bin/console make:migration
```
- Veritabanı şeması değişikliklerini algılar
- Migration dosyası oluşturur

### 7. Migration Çalıştırma
```bash
php bin/console doctrine:migrations:migrate
```
- Migration'ları veritabanına uygular

## Modül Adı Kuralları

- Modül adı otomatik olarak PascalCase formatına dönüştürülür
- Klasör adları modül adıyla aynı olur
- Namespace'ler modül adına göre ayarlanır

## Örnek Modüller

- **Siparis**: Sipariş yönetimi
- **Urun**: Ürün katalog yönetimi
- **Musteri**: Müşteri yönetimi
- **Kategori**: Kategori yönetimi

## Çalışma Akışı

1. **Klasör yapısını oluşturun**:
   ```bash
   php bin/console app:create-module
   ```

2. **Entity oluşturun**:
   ```bash
   php bin/console make:entity [ModulAdi]
   ```

3. **CRUD oluşturun** (veya ayrı ayrı):
   ```bash
   php bin/console make:crud [ModulAdi]
   ```

4. **Migration oluşturun**:
   ```bash
   php bin/console make:migration
   ```

5. **Migration'ı çalıştırın**:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. **Template'leri özelleştirin**:
   - `templates/[moduladi]/index.html.twig`
   - `templates/[moduladi]/new.html.twig`
   - `templates/[moduladi]/show.html.twig`
   - `templates/[moduladi]/edit.html.twig`

## Avantajlar

- **Temiz Yapı**: Sadece klasör yapısı oluşturur, dosya içeriklerini Symfony'ye bırakır
- **Esneklik**: Symfony'nin kendi komutlarını kullanarak dosyaları özelleştirebilirsiniz
- **Standart**: Symfony best practices'e uygun
- **Hızlı**: Klasör yapısını hızlıca oluşturur
- **Modüler**: Her modül kendi klasör yapısında organize edilir

## Notlar

- Klasör yapısı oluşturulduktan sonra dosyaları manuel olarak oluşturmanız gerekir
- Symfony'nin kendi komutları dosya oluşturma için optimize edilmiştir
- Modüler mimari prensiplerine uygun yapılandırılmıştır
- Mevcut proje yapısına uygun olarak tasarlanmıştır 