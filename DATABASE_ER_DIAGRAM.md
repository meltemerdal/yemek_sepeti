# Yemeksepeti - Veritabanı ER Diyagramı

## Entity-Relationship Diagram

```mermaid
erDiagram
    Users ||--o{ Addresses : "sahip olur"
    Users ||--o{ Orders : "verir"
    Users ||--o{ Restaurants : "yönetir"
    Users ||--o{ Favorites : "favorilere ekler"
    
    Restaurants ||--o{ MenuItems : "içerir"
    Restaurants ||--o{ Orders : "alır"
    Restaurants ||--o{ Favorites : "favorilenen"
    
    Orders ||--o{ OrderDetails : "içerir"
    Orders }o--|| Addresses : "gönderilir"
    
    MenuItems ||--o{ OrderDetails : "sipariş edilir"
    
    Categories ||--o{ Restaurants : "kategorize eder"

    Users {
        int UserID PK "Primary Key"
        nvarchar FullName "Kullanıcı Adı"
        nvarchar Email UK "E-posta (Unique)"
        nvarchar Phone "Telefon"
        nvarchar Password "Şifre"
        nvarchar UserType "Customer/RestaurantOwner/Admin"
        datetime CreatedDate "Oluşturulma Tarihi"
        bit IsActive "Aktif mi?"
    }

    Addresses {
        int AddressID PK "Primary Key"
        int UserID FK "User Foreign Key"
        nvarchar Title "Adres Başlığı (Ev, İş)"
        nvarchar AddressText "Adres Metni"
        nvarchar District "İlçe"
        nvarchar City "Şehir"
        bit IsDefault "Varsayılan Adres"
        datetime CreatedDate "Oluşturulma Tarihi"
    }

    Restaurants {
        int RestaurantID PK "Primary Key"
        int OwnerUserID FK "Sahip User ID"
        nvarchar Name "Restoran Adı"
        nvarchar Category "Kategori"
        nvarchar Address "Adres"
        nvarchar Phone "Telefon"
        nvarchar Description "Açıklama"
        nvarchar ImageUrl "Görsel URL"
        decimal Rating "Puan (0-5)"
        decimal MinOrderAmount "Min. Sipariş Tutarı"
        int DeliveryTime "Teslimat Süresi (dk)"
        nvarchar Status "pending/approved/rejected/suspended"
        bit IsActive "Aktif mi?"
        bit IsOpen "Açık mı?"
        datetime CreatedDate "Oluşturulma Tarihi"
    }

    MenuItems {
        int MenuItemID PK "Primary Key"
        int RestaurantID FK "Restaurant Foreign Key"
        nvarchar Name "Ürün Adı"
        nvarchar Description "Açıklama"
        nvarchar ImageURL "Görsel URL"
        decimal Price "Fiyat"
        nvarchar Category "Kategori"
        bit IsAvailable "Mevcut mu?"
        int Stock "Stok Adedi"
        datetime CreatedDate "Oluşturulma Tarihi"
    }

    Orders {
        int OrderID PK "Primary Key"
        int UserID FK "User Foreign Key"
        int RestaurantID FK "Restaurant Foreign Key"
        int AddressID FK "Address Foreign Key"
        int UserOrderNumber "Kullanıcıya Özel Sıra No"
        datetime OrderDate "Sipariş Tarihi"
        decimal TotalAmount "Toplam Tutar"
        decimal DeliveryFee "Teslimat Ücreti"
        nvarchar Status "Hazırlanıyor/Yolda/Teslim Edildi/İptal"
        nvarchar PaymentMethod "Ödeme Yöntemi"
        nvarchar Note "Not"
    }

    OrderDetails {
        int OrderDetailID PK "Primary Key"
        int OrderID FK "Order Foreign Key"
        int MenuItemID FK "MenuItem Foreign Key"
        int Quantity "Adet"
        decimal UnitPrice "Birim Fiyat"
        decimal Subtotal "Ara Toplam"
        datetime CreatedDate "Oluşturulma Tarihi"
    }

    Categories {
        int CategoryID PK "Primary Key"
        nvarchar Name UK "Kategori Adı (Unique)"
        bit IsActive "Aktif mi?"
        datetime CreatedDate "Oluşturulma Tarihi"
    }

    Favorites {
        int FavoriteID PK "Primary Key"
        int UserID FK "User Foreign Key"
        int RestaurantID FK "Restaurant Foreign Key"
        datetime CreatedAt "Eklenme Tarihi"
    }

    UserActivityLog {
        int LogID PK "Primary Key"
        int UserID "User ID"
        nvarchar Activity "Aktivite Açıklaması"
        datetime LogDate "Log Tarihi"
    }

    OrderStatusLog {
        int LogID PK "Primary Key"
        int OrderID "Order ID"
        nvarchar OldStatus "Eski Durum"
        nvarchar NewStatus "Yeni Durum"
        datetime ChangedDate "Değişiklik Tarihi"
    }
```

## Tablo Açıklamaları

### Ana Tablolar

#### **Users (Kullanıcılar)**
- Sistemdeki tüm kullanıcıları tutar (müşteriler, restoran sahipleri, adminler)
- `UserType`: Customer, RestaurantOwner veya Admin
- Email benzersiz (unique) olmalıdır

#### **Restaurants (Restoranlar)**
- Platformdaki tüm restoranları saklar
- `Status`: pending (beklemede), approved (onaylı), rejected (reddedildi), suspended (askıya alındı)
- `OwnerUserID`: Restoranı yöneten kullanıcının ID'si

#### **MenuItems (Menü Öğeleri)**
- Her restorana ait ürünleri içerir
- Kategori, fiyat, görsel ve stok bilgisi

#### **Orders (Siparişler)**
- Müşteri siparişlerini saklar
- `UserOrderNumber`: Her kullanıcı için 1'den başlayan sipariş numarası
- Durum takibi: Hazırlanıyor → Yola Çıktı → Teslim Edildi

#### **OrderDetails (Sipariş Detayları)**
- Siparişteki her ürünün detaylarını tutar
- Adet, birim fiyat ve ara toplam bilgileri

#### **Addresses (Adresler)**
- Kullanıcıların kayıtlı teslimat adreslerini saklar
- Bir adres varsayılan (IsDefault) olarak işaretlenebilir

#### **Categories (Kategoriler)**
- Restoran kategorilerini tanımlar
- Örnek: Burger, Pizza, Kebap, Tatlı, Kahve

#### **Favorites (Favoriler)**
- Kullanıcıların favori restoranlarını saklar
- Her kullanıcı-restoran çifti benzersiz olmalıdır

### Log Tabloları

#### **UserActivityLog**
- Kullanıcı aktivitelerini izlemek için trigger tarafından kullanılır

#### **OrderStatusLog**
- Sipariş durum değişikliklerini izlemek için trigger tarafından kullanılır

## İlişkiler

1. **Users → Addresses**: Bir kullanıcının birden fazla adresi olabilir (1:N)
2. **Users → Orders**: Bir kullanıcı birden fazla sipariş verebilir (1:N)
3. **Users → Restaurants**: Bir restoran sahibi birden fazla restoranı yönetebilir (1:N)
4. **Users → Favorites**: Bir kullanıcının birden fazla favori restoranı olabilir (1:N)
5. **Restaurants → MenuItems**: Bir restoranın birden fazla menü öğesi vardır (1:N)
6. **Restaurants → Orders**: Bir restoran birden fazla sipariş alabilir (1:N)
7. **Orders → OrderDetails**: Bir siparişte birden fazla ürün olabilir (1:N)
8. **Orders → Addresses**: Her sipariş bir adrese gönderilir (N:1)
9. **MenuItems → OrderDetails**: Bir menü öğesi birden fazla siparişte olabilir (1:N)

## Kısıtlamalar (Constraints)

- **Primary Keys**: Tüm tablolarda IDENTITY(1,1) ile otomatik artan birincil anahtarlar
- **Foreign Keys**: İlişkili tablolar arasında referans bütünlüğü
- **Unique Constraints**: 
  - Users.Email
  - Categories.Name
  - Favorites (UserID, RestaurantID) kombinasyonu
- **Default Values**: CreatedDate, IsActive, Rating, Status gibi alanlar için varsayılan değerler

## Trigger'lar

1. **trg_SetUserOrderNumber**: Yeni sipariş eklendiğinde otomatik UserOrderNumber atar
2. **trg_LogUserActivity**: Kullanıcı işlemlerini UserActivityLog tablosuna kaydeder
3. **trg_LogOrderStatus**: Sipariş durum değişikliklerini OrderStatusLog tablosuna kaydeder

## Stored Procedures

- **sp_GetRestaurants**: Restoranları filtreler ve listeler
- **sp_GetMenuItems**: Belirli bir restoranın menü öğelerini getirir
- **sp_CreateOrder**: Yeni sipariş oluşturur
- **sp_UpdateOrderStatus**: Sipariş durumunu günceller

## Views

- **vw_OrderDetails**: Sipariş detaylarını zenginleştirilmiş halde gösterir
- Kullanıcı, restoran, ürün bilgilerini birleştirerek kolay raporlama sağlar
