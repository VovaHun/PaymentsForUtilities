package dev.akat.smartmeter

import android.app.Application
import dev.akat.smartmeter.di.AppComponent
import dev.akat.smartmeter.di.AppModule
import dev.akat.smartmeter.di.DaggerAppComponent

class MeterApplication : Application() {

    val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        DaggerAppComponent
            .builder()
            .appModule(AppModule(this))
            .build()
    }
}