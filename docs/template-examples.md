# Template Örnekleri

Modül oluşturulduktan sonra, aşağıdaki template dosyalarını oluşturmanız gerekecektir.

## Template Dosya Yapısı

```
templates/
└── [moduladi]/
    ├── index.html.twig
    ├── new.html.twig
    ├── show.html.twig
    └── edit.html.twig
```

## 1. index.html.twig (Liste Sayfası)

```twig
{% extends 'base.html.twig' %}

{% block title %}{{ modul_adi|title }} Listesi{% endblock %}

{% block body %}
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>{{ modul_adi|title }} Listesi</h1>
                    <a href="{{ path('app_' ~ modul_adi ~ '_new') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni {{ modul_adi|title }}
                    </a>
                </div>

                {% for message in app.flashes('success') %}
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                {% endfor %}

                <div class="card">
                    <div class="card-body">
                        {% if uruns is empty %}
                            <p class="text-muted text-center py-4">Henüz {{ modul_adi }} bulunmamaktadır.</p>
                        {% else %}
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Ad</th>
                                            <th>Açıklama</th>
                                            <th>Durum</th>
                                            <th>Oluşturulma Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for urun in uruns %}
                                            <tr>
                                                <td>{{ urun.id }}</td>
                                                <td>{{ urun.name }}</td>
                                                <td>{{ urun.description|slice(0, 50) }}{% if urun.description|length > 50 %}...{% endif %}</td>
                                                <td>
                                                    {% if urun.isActive %}
                                                        <span class="badge bg-success">Aktif</span>
                                                    {% else %}
                                                        <span class="badge bg-secondary">Pasif</span>
                                                    {% endif %}
                                                </td>
                                                <td>{{ urun.createdAt|date('d.m.Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ path('app_' ~ modul_adi ~ '_show', {'id': urun.id}) }}" 
                                                           class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ path('app_' ~ modul_adi ~ '_edit', {'id': urun.id}) }}" 
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="post" action="{{ path('app_' ~ modul_adi ~ '_delete', {'id': urun.id}) }}" 
                                                              style="display: inline-block" 
                                                              onsubmit="return confirm('Bu {{ modul_adi }}yi silmek istediğinizden emin misiniz?')">
                                                            <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ urun.id) }}">
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        {% endfor %}
                                    </tbody>
                                </table>
                            </div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
```

## 2. new.html.twig (Yeni Kayıt Sayfası)

```twig
{% extends 'base.html.twig' %}

{% block title %}Yeni {{ modul_adi|title }}{% endblock %}

{% block body %}
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Yeni {{ modul_adi|title }} Oluştur</h2>
                    </div>
                    <div class="card-body">
                        {{ form_start(form) }}
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    {{ form_label(form.name, 'Ad', {'label_attr': {'class': 'form-label'}}) }}
                                    {{ form_widget(form.name, {'attr': {'class': 'form-control'}}) }}
                                    {{ form_errors(form.name, {'attr': {'class': 'text-danger small'}}) }}
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    {{ form_label(form.description, 'Açıklama', {'label_attr': {'class': 'form-label'}}) }}
                                    {{ form_widget(form.description, {'attr': {'class': 'form-control'}}) }}
                                    {{ form_errors(form.description, {'attr': {'class': 'text-danger small'}}) }}
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        {{ form_widget(form.isActive, {'attr': {'class': 'form-check-input'}}) }}
                                        {{ form_label(form.isActive, 'Aktif', {'label_attr': {'class': 'form-check-label'}}) }}
                                        {{ form_errors(form.isActive, {'attr': {'class': 'text-danger small'}}) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ path('app_' ~ modul_adi ~ '_index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Geri
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Kaydet
                                </button>
                            </div>
                        {{ form_end(form) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
```

## 3. show.html.twig (Detay Sayfası)

```twig
{% extends 'base.html.twig' %}

{% block title %}{{ modul_adi|title }} Detayı{% endblock %}

{% block body %}
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">{{ modul_adi|title }} Detayı</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>ID:</strong>
                                <p>{{ urun.id }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Ad:</strong>
                                <p>{{ urun.name }}</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Açıklama:</strong>
                                <p>{{ urun.description|default('Açıklama bulunmamaktadır.')|nl2br }}</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Durum:</strong>
                                <p>
                                    {% if urun.isActive %}
                                        <span class="badge bg-success">Aktif</span>
                                    {% else %}
                                        <span class="badge bg-secondary">Pasif</span>
                                    {% endif %}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Oluşturulma Tarihi:</strong>
                                <p>{{ urun.createdAt|date('d.m.Y H:i:s') }}</p>
                            </div>
                        </div>
                        
                        {% if urun.updatedAt %}
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Güncellenme Tarihi:</strong>
                                <p>{{ urun.updatedAt|date('d.m.Y H:i:s') }}</p>
                            </div>
                        </div>
                        {% endif %}
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ path('app_' ~ modul_adi ~ '_index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Geri
                            </a>
                            <div>
                                <a href="{{ path('app_' ~ modul_adi ~ '_edit', {'id': urun.id}) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Düzenle
                                </a>
                                <form method="post" action="{{ path('app_' ~ modul_adi ~ '_delete', {'id': urun.id}) }}" 
                                      style="display: inline-block" 
                                      onsubmit="return confirm('Bu {{ modul_adi }}yi silmek istediğinizden emin misiniz?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ urun.id) }}">
                                    <button class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
```

## 4. edit.html.twig (Düzenleme Sayfası)

```twig
{% extends 'base.html.twig' %}

{% block title %}{{ modul_adi|title }} Düzenle{% endblock %}

{% block body %}
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">{{ modul_adi|title }} Düzenle</h2>
                    </div>
                    <div class="card-body">
                        {{ form_start(form) }}
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    {{ form_label(form.name, 'Ad', {'label_attr': {'class': 'form-label'}}) }}
                                    {{ form_widget(form.name, {'attr': {'class': 'form-control'}}) }}
                                    {{ form_errors(form.name, {'attr': {'class': 'text-danger small'}}) }}
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    {{ form_label(form.description, 'Açıklama', {'label_attr': {'class': 'form-label'}}) }}
                                    {{ form_widget(form.description, {'attr': {'class': 'form-control'}}) }}
                                    {{ form_errors(form.description, {'attr': {'class': 'text-danger small'}}) }}
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        {{ form_widget(form.isActive, {'attr': {'class': 'form-check-input'}}) }}
                                        {{ form_label(form.isActive, 'Aktif', {'label_attr': {'class': 'form-check-label'}}) }}
                                        {{ form_errors(form.isActive, {'attr': {'class': 'text-danger small'}}) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ path('app_' ~ modul_adi ~ '_show', {'id': urun.id}) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Geri
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Güncelle
                                </button>
                            </div>
                        {{ form_end(form) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
```

## Kullanım Notları

1. **Değişken Adları**: Template'lerde `{{ modul_adi }}` ve `{{ uruns }}` gibi değişken adlarını kendi modülünüze göre değiştirin.

2. **Route Adları**: `app_urun_index` gibi route adlarını kendi modülünüze göre güncelleyin.

3. **Bootstrap CSS**: Template'ler Bootstrap 5 kullanır. Eğer farklı bir CSS framework kullanıyorsanız, class'ları güncelleyin.

4. **Font Awesome**: İkonlar için Font Awesome kullanılmıştır. Eğer farklı bir ikon kütüphanesi kullanıyorsanız, ikon class'larını güncelleyin.

5. **CSRF Token**: Silme işlemleri için CSRF token kullanılmıştır. Symfony'nin güvenlik ayarlarınızın doğru olduğundan emin olun.

## Özelleştirme

Template'leri ihtiyaçlarınıza göre özelleştirebilirsiniz:

- Farklı CSS framework'leri kullanabilirsiniz
- İkon kütüphanelerini değiştirebilirsiniz
- Layout'u projenizin tasarımına uygun hale getirebilirsiniz
- Ek alanlar ekleyebilirsiniz
- Filtreleme ve arama özellikleri ekleyebilirsiniz 