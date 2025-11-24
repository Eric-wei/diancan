-- ========================================
-- 添加测试门店数据
-- ========================================
-- 注意：请根据实际情况修改经纬度、地址等信息

-- 插入测试门店（KFC 风格）
INSERT INTO `fa_fzdc_store` (
    `title`,
    `alias_title`,
    `address`,
    `lon`,
    `lat`,
    `desc`,
    `tel`,
    `image`,
    `images`,
    `start_times`,
    `end_times`,
    `subscribe_switch`,
    `delivery_switch`,
    `takeaway_switch`,
    `trade_switch`,
    `state`,
    `weigh`,
    `createtime`,
    `updatetime`,
    `deletetime`,
    `content`,
    `iscwf`,
    `cwf`,
    `tz_url`
) VALUES (
    'KFC 测试门店',                          -- 门店名称
    'KFC Test Store',                        -- 别名
    '北京市朝阳区建国路88号',                -- 地址
    '116.463407',                            -- 经度（北京）
    '39.915119',                             -- 纬度（北京）
    '欢迎光临KFC，享受美味炸鸡！',           -- 描述
    '400-882-3823',                          -- 电话
    '/uploads/store/default.jpg',            -- 缩略图
    '/uploads/store/banner1.jpg,/uploads/store/banner2.jpg',  -- 轮播图
    '09:00:00',                              -- 营业开始时间
    '22:00:00',                              -- 营业结束时间
    1,                                       -- 预订开关：1=开启
    1,                                       -- 自提开关：1=开启
    1,                                       -- 外卖开关：1=开启
    1,                                       -- 营业开关：1=开启
    1,                                       -- 状态：1=正常
    1,                                       -- 权重
    UNIX_TIMESTAMP(),                        -- 创建时间
    UNIX_TIMESTAMP(),                        -- 更新时间
    NULL,                                    -- 删除时间
    '<p>KFC 测试门店，提供优质的快餐服务</p>',  -- 详细内容
    0,                                       -- iscwf
    0,                                       -- cwf
    ''                                       -- tz_url
);

-- 查看刚插入的门店
SELECT * FROM fa_fzdc_store WHERE title = 'KFC 测试门店';

-- ========================================
-- 如果已有门店但状态不对，可以使用以下 SQL 更新
-- ========================================

-- 启用所有门店
-- UPDATE fa_fzdc_store SET state = 1 WHERE deletetime IS NULL;

-- 开启所有开关
-- UPDATE fa_fzdc_store SET 
--     delivery_switch = 1,
--     takeaway_switch = 1,
--     trade_switch = 1
-- WHERE deletetime IS NULL;

-- 设置营业时间为全天
-- UPDATE fa_fzdc_store SET 
--     start_times = '00:00:00',
--     end_times = '23:59:59'
-- WHERE deletetime IS NULL;

