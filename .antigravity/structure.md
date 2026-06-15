app/
├── Enums/
│   ├── ProspectStatus.php      (Cold, Warm, Hot)
│   ├── CriteriaType.php        (Benefit, Cost)
│   └── UserRole.php            (Admin, Sales, Pimpinan)
├── Http/
│   ├── Controllers/
│   │   ├── Auth/               (Breeze/Default)
│   │   ├── Admin/              (CriteriaController, UserController)
│   │   ├── Sales/              (ProspectController, InteractionController)
│   │   └── AnalysisController.php (Menampilkan hasil SPK)
│   ├── Requests/               (StoreProspectRequest, UpdateCriteriaRequest)
├── Models/
│   ├── User.php
│   ├── Prospect.php
│   ├── Criteria.php
│   ├── SubCriteria.php         (PENTING: Untuk konversi nilai ke angka)
│   └── Evaluation.php          (Tabel nilai pivot)
├── Services/
│   └── DecisionSupport/
│       ├── NormalizationService.php  (Menghitung matriks R)
│       ├── TopsisEngine.php          (Logika utama jarak ideal +/-)
│       └── RankingFormatter.php      (Formatting output untuk view)