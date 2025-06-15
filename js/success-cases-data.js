// 講座經歷數據 (從提供的文件中提取)
const lectureData = [
    {
        year: "2025",
        title: "參賽提案大補帖",
        organization: "南投縣政府計畫處",
        category: "講座資歷",
        description: "提供參賽提案的完整指導，協助參賽者掌握提案技巧與評審重點",
        tags: ["提案技巧", "競賽指導", "評審分析"]
    },
    {
        year: "2025",
        title: "計畫書撰寫實戰工作坊-生成式AI工具課程",
        organization: "勞動力發展署雲嘉南分署",
        category: "講座資歷",
        description: "結合AI科技與傳統提案技巧，提升計畫書撰寫效率與品質",
        tags: ["AI工具", "撰寫技巧", "工作坊"]
    },
    {
        year: "2024",
        title: "花蓮練習曲山海講堂-無痛入門政府補助提案技巧",
        organization: "練習曲",
        category: "講座資歷",
        description: "以易懂的方式教授政府補助申請技巧與實務操作",
        tags: ["政府補助", "入門教學", "實務操作"]
    },
    {
        year: "2024",
        title: "政府補助計畫撰寫",
        organization: "音桃族創育坊",
        category: "講座資歷",
        description: "針對創業團隊提供政府補助計畫撰寫指導",
        tags: ["創業輔導", "計畫撰寫", "補助申請"]
    },
    {
        year: "2024",
        title: "服務網絡推動專案-夢幻舞台上的城鄉創生交響曲",
        organization: "南投縣工商發展投資策進會",
        category: "講座資歷",
        description: "建構跨域合作網絡，推動城鄉創生永續發展",
        tags: ["城鄉創生", "跨域合作", "永續發展"]
    },
    {
        year: "2024",
        title: "政府補助計畫撰寫",
        organization: "花蓮縣瑞穗鄉公所",
        category: "講座資歷",
        description: "協助公所同仁了解政府補助計畫撰寫要點與技巧",
        tags: ["政府機關", "計畫撰寫", "公務培訓"]
    },
    {
        year: "2024",
        title: "第一次寫創業計畫就上手",
        organization: "國立雲林科技大學",
        category: "講座資歷",
        description: "針對大學生創業需求，提供創業計畫書撰寫指導",
        tags: ["大學教育", "創業計畫", "學生輔導"]
    },
    {
        year: "2024",
        title: "政府補助計畫撰寫",
        organization: "南開科技大學",
        category: "講座資歷",
        description: "為技職院校師生提供政府補助申請實務指導",
        tags: ["技職教育", "補助申請", "師生培訓"]
    },
    {
        year: "2024",
        title: "政府補助計畫撰寫",
        organization: "北區輔導中心",
        category: "講座資歷",
        description: "協助北區創業團隊掌握政府補助申請技巧",
        tags: ["創業輔導", "北區服務", "補助申請"]
    },
    {
        year: "2024",
        title: "政府補助計畫撰寫",
        organization: "苗栗縣青創基地",
        category: "講座資歷",
        description: "為苗栗青年創業者提供補助計畫撰寫指導",
        tags: ["青年創業", "苗栗地區", "創業基地"]
    },
    {
        year: "2024",
        title: "政府補助計畫撰寫",
        organization: "彰化縣青創基地",
        category: "講座資歷",
        description: "協助彰化青年創業者申請政府補助資源",
        tags: ["青年創業", "彰化地區", "創業支持"]
    },
    {
        year: "2023",
        title: "計畫提案實務工作坊-集集廊帶發展歷程",
        organization: "南投市公所",
        category: "講座資歷",
        description: "結合集集廊帶發展經驗，分享提案實務操作技巧",
        tags: ["地方發展", "提案實務", "經驗分享"]
    },
    {
        year: "2023",
        title: "社區計畫撰寫課程",
        organization: "屏東縣政府",
        category: "講座資歷",
        description: "協助屏東縣社區組織提升計畫撰寫能力",
        tags: ["社區發展", "屏東地區", "計畫撰寫"]
    },
    {
        year: "2023",
        title: "以提案力建立影響力",
        organization: "國立雲林科技大學",
        category: "講座資歷",
        description: "教授如何透過有效提案建立個人與組織影響力",
        tags: ["影響力建立", "提案技巧", "大學教育"]
    }
];

// 輔導單位數據 (從提供的文件中提取)
const consultingUnitsData = [
    {
        title: "三赫健康餐盒",
        location: "健康餐飲業",
        category: "輔導單位",
        description: "協助健康餐盒業者建立品牌定位與市場拓展策略",
        tags: ["餐飲業", "健康食品", "品牌建立"]
    },
    {
        title: "南投縣水里鄉永興牛轀轆社區發展協會",
        location: "南投縣水里鄉",
        category: "輔導單位",
        description: "協助社區發展協會建立永續經營模式，推動在地文化保存與產業發展",
        tags: ["社區營造", "文化保存", "產業發展"]
    },
    {
        title: "南投縣集集鎮形象商圈觀光發展協會",
        location: "南投縣集集鎮",
        category: "輔導單位",
        description: "推動集集鎮觀光商圈發展，提升在地觀光品質與商業競爭力",
        tags: ["觀光發展", "商圈經營", "品牌形象"]
    },
    {
        title: "社團法人南投縣觀光品牌推廣協會",
        location: "南投縣",
        category: "輔導單位",
        description: "協助推廣南投縣觀光品牌，建立完整的觀光行銷策略",
        tags: ["品牌推廣", "觀光行銷", "策略規劃"]
    },
    {
        title: "有限責任南投縣仁愛鄉原住民果樹生產合作社",
        location: "南投縣仁愛鄉",
        category: "輔導單位",
        description: "輔導原住民果樹生產合作社發展，提升產品品質與市場競爭力",
        tags: ["原住民產業", "合作社經營", "農產品牌"]
    },
    {
        title: "村落印象有限公司",
        location: "南投縣",
        category: "輔導單位",
        description: "協助地方文化創意產業發展，打造特色文化體驗服務",
        tags: ["文創產業", "體驗設計", "文化行銷"]
    },
    {
        title: "社團法人台灣基督教福星全人關懷協會",
        location: "社會關懷",
        category: "輔導單位",
        description: "協助社會關懷組織建立服務模式與資源整合策略",
        tags: ["社會關懷", "非營利組織", "服務模式"]
    },
    {
        title: "保證責任南投縣可可生產合作社",
        location: "南投縣",
        category: "輔導單位",
        description: "輔導可可生產合作社建立品牌與銷售通路",
        tags: ["農業合作社", "可可產業", "品牌建立"]
    },
    {
        title: "哈佛書店",
        location: "書店經營",
        category: "輔導單位",
        description: "協助實體書店轉型與經營策略規劃",
        tags: ["書店經營", "文化產業", "轉型策略"]
    },
    {
        title: "圓圈圈舞團",
        location: "表演藝術",
        category: "輔導單位",
        description: "協助表演藝術團體建立營運模式與推廣策略",
        tags: ["表演藝術", "文化團體", "營運模式"]
    }
];

// 輔導計畫數據 (從提供的文件中提取)
const consultingPlansData = [
    {
        title: "地方創生事業構想書",
        organization: "國家發展委員會",
        category: "輔導計畫",
        description: "協助地方團隊規劃創生事業構想，打造可持續發展的地方特色產業",
        tags: ["地方創生", "事業規劃", "產業發展"]
    },
    {
        title: "小型企業創新研發計畫(SBIR)",
        organization: "經濟部",
        category: "輔導計畫",
        description: "輔導中小企業申請SBIR計畫，提升技術創新能力與競爭優勢",
        tags: ["技術創新", "研發補助", "企業輔導"]
    },
    {
        title: "青年創業貸款",
        organization: "經濟部",
        category: "輔導計畫",
        description: "協助青年創業者申請創業貸款，提供資金與諮詢服務",
        tags: ["青年創業", "創業貸款", "資金協助"]
    },
    {
        title: "多元就業開發方案",
        organization: "勞動部",
        category: "輔導計畫",
        description: "推動多元就業機會，協助弱勢族群獲得工作技能與就業機會",
        tags: ["就業輔導", "技能培訓", "弱勢關懷"]
    },
    {
        title: "社區營造及村落文化發展計畫",
        organization: "文化部",
        category: "輔導計畫",
        description: "推動社區文化保存與發展，建立在地文化特色與認同",
        tags: ["社區營造", "文化保存", "在地認同"]
    },
    {
        title: "推動中小企業城鄉創生轉型輔導計畫(SBTR)",
        organization: "經濟部",
        category: "輔導計畫",
        description: "協助中小企業透過城鄉創生進行轉型升級",
        tags: ["城鄉創生", "企業轉型", "中小企業"]
    },
    {
        title: "服務業創新研發計畫(SIIR)",
        organization: "經濟部",
        category: "輔導計畫",
        description: "推動服務業創新研發，提升服務品質與競爭力",
        tags: ["服務業", "創新研發", "競爭力提升"]
    },
    {
        title: "文化創意產業青年創業貸款",
        organization: "文化部",
        category: "輔導計畫",
        description: "協助文創產業青年創業者申請專案貸款",
        tags: ["文創產業", "青年創業", "創業貸款"]
    },
    {
        title: "教育部青年發展署U-start創新創業計畫",
        organization: "教育部",
        category: "輔導計畫",
        description: "支持青年創新創業，提供資金與輔導資源",
        tags: ["青年創業", "創新計畫", "教育支持"]
    },
    {
        title: "農村社區企業經營輔導計畫",
        organization: "農業部",
        category: "輔導計畫",
        description: "輔導農村社區發展特色產業與企業化經營",
        tags: ["農村發展", "社區企業", "產業輔導"]
    }
];

// 合併所有數據
const allCasesData = [
    ...lectureData,
    ...consultingUnitsData,
    ...consultingPlansData
];

// 統計數據
const statsData = {
    lectures: lectureData.length,
    consultingUnits: consultingUnitsData.length,
    consultingPlans: consultingPlansData.length,
    totalYears: 7
}; 