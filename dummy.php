INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `lrl`, `rrl`, `direct_sponser_id`, `side`, `user_unique_address`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@mail.com', NULL, '$2y$10$hykcc4oRDe4V7hWEeYJa0.6Ta6vryh.GDn1iimv9LZleq3R1gKgaa', 'admin', 'QBC952934279', 'QBC608426840', NULL, '', 'QBC4G5Y3NghrI1EZFWAUv0PHdkxm27uCLa8OzjQKfeDJwX6bMlps9cntBiVRqTSy', 1, NULL, '2023-05-04 11:39:09', '2023-05-05 09:32:47')

INSERT INTO `user_metas` (`id`, `user_id`, `total_capping`, `remain_capping`, `total_plans`, `total_plans_active`, `total_plans_expired`, `total_roi_income`, `total_level_income`, `total_matching_income`, `total_royality_income`, `left_carry_forward`, `right_carry_forward`, `is_tritons`, `is_spartans`, `is_federal`, `is_capita`, `is_primmest`, `is_master`, `is_community`, `created_at`, `updated_at`) VALUES
(1, 1, '10000.00000000', '0.00000000', 0, 0, 0, '0.00000000', '0.00000000', '10000.00000000', '0.00000000', '30300.00000000', '0.00000000', 0, 0, 0, 1, 0, 0, 0, NULL, '2023-05-09 13:25:28')



1. add isroyality colums in usermeta table
2. add code in matching income for user achieved royality or not
3 add royality command for royality income
4. start tritons , spartans income and federal
