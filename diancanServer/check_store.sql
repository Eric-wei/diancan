-- ========================================
-- 检查门店数据
-- ========================================

-- 1. 查看所有门店
SELECT 
    id,
    title,
    state,
    delivery_switch,
    takeaway_switch,
    trade_switch,
    start_times,
    end_times,
    deletetime
FROM fa_fzdc_store
ORDER BY id;

-- 2. 查看正常状态的门店
SELECT 
    id,
    title,
    address,
    state,
    delivery_switch AS '自提开关',
    takeaway_switch AS '外卖开关',
    trade_switch AS '营业开关',
    start_times AS '开始时间',
    end_times AS '结束时间'
FROM fa_fzdc_store
WHERE state = 1 AND deletetime IS NULL;

-- 3. 统计门店数量
SELECT 
    COUNT(*) AS '总门店数',
    SUM(CASE WHEN state = 1 AND deletetime IS NULL THEN 1 ELSE 0 END) AS '正常门店数',
    SUM(CASE WHEN delivery_switch = 1 THEN 1 ELSE 0 END) AS '支持自提数',
    SUM(CASE WHEN takeaway_switch = 1 THEN 1 ELSE 0 END) AS '支持外卖数'
FROM fa_fzdc_store;

