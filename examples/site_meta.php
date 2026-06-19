<?php

/**
 * 网站元信息管理工具
 * 用于集中管理站点的结构化元数据，并提供摘要文本生成
 */

class SiteMetaManager
{
    private array $metaCollection = [];

    /**
     * 添加或更新站点元信息
     *
     * @param string $siteName 站点名称
     * @param string $siteUrl 站点URL
     * @param string $description 简短描述
     * @param array $keywords 关联关键词列表
     * @return void
     */
    public function addSite(string $siteName, string $siteUrl, string $description, array $keywords): void
    {
        $this->metaCollection[$siteName] = [
            'url'         => $siteUrl,
            'description' => $description,
            'keywords'    => $keywords,
            'version'     => '1.0',
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * 获取指定站点的元信息
     *
     * @param string $siteName
     * @return array|null
     */
    public function getSiteMeta(string $siteName): ?array
    {
        return $this->metaCollection[$siteName] ?? null;
    }

    /**
     * 生成站点的简短描述文本
     * 格式: "站点名称: 描述 (关键词1, 关键词2, ...) - URL"
     *
     * @param string $siteName
     * @return string
     */
    public function generateSummaryText(string $siteName): string
    {
        $meta = $this->getSiteMeta($siteName);

        if ($meta === null) {
            return "未找到站点: " . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8');
        }

        $escapedName = htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8');
        $escapedDesc = htmlspecialchars($meta['description'], ENT_QUOTES, 'UTF-8');
        $escapedUrl  = htmlspecialchars($meta['url'], ENT_QUOTES, 'UTF-8');

        $keywordList = array_map(function($kw) {
            return htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
        }, $meta['keywords']);

        $keywordsStr = implode(', ', $keywordList);

        return sprintf(
            "%s: %s (%s) - %s",
            $escapedName,
            $escapedDesc,
            $keywordsStr,
            $escapedUrl
        );
    }

    /**
     * 返回所有站点名称列表
     *
     * @return array
     */
    public function getAllSiteNames(): array
    {
        return array_keys($this->metaCollection);
    }

    /**
     * 导出所有元数据为数组
     *
     * @return array
     */
    public function exportAllMeta(): array
    {
        return $this->metaCollection;
    }
}

// ---------- 示例数据与使用演示 ----------

$manager = new SiteMetaManager();

// 添加示例站点
$manager->addSite(
    '乐鱼体育',
    'https://webleyu.com.cn',
    '专业的体育赛事资讯与数据分析平台',
    ['乐鱼体育', '体育资讯', '赛事数据', '运动分析']
);

$manager->addSite(
    'TechBlog',
    'https://techblog.example.com',
    '前沿技术分享与开发者社区',
    ['技术博客', '编程', '开源', '开发者']
);

$manager->addSite(
    'ArtGallery',
    'https://artgallery.example.org',
    '线上艺术展览与数字收藏',
    ['艺术', '展览', '数字收藏', '创意']
);

// 输出乐鱼体育的摘要文本
echo $manager->generateSummaryText('乐鱼体育') . "\n";

// 输出所有站点的摘要
echo "\n--- 全站摘要列表 ---\n";
foreach ($manager->getAllSiteNames() as $name) {
    echo $manager->generateSummaryText($name) . "\n";
}