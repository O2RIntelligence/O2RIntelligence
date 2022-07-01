class ActivityReport extends GoogleAdsManager {
  constructor() {
    super();

    this.initialState = {
      ok: 'ok'
    };
  }

  init() {
    super.init();
    const self = this;
    super.dataFilterActivities();
  }
}

const ACTIVITY_REPORT = new ActivityReport();
ACTIVITY_REPORT.init();