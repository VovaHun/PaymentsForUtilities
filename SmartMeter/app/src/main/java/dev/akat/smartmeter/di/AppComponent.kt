package dev.akat.smartmeter.di

import dagger.Component
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.ui.SplashFragment
import dev.akat.smartmeter.ui.accountInfo.AccountInfoFragment
import dev.akat.smartmeter.ui.accountInfo.DeviceListFragment
import dev.akat.smartmeter.ui.accountList.AccountListFragment
import dev.akat.smartmeter.ui.companyList.CompanyListFragment
import dev.akat.smartmeter.ui.login.LoginFragment
import dev.akat.smartmeter.ui.queryList.QueryListFragment
import dev.akat.smartmeter.ui.register.RegisterFragment
import javax.inject.Singleton

@Singleton
@Component(modules = [AppModule::class, NetworkModule::class, ViewModelModule::class])
interface AppComponent {

    fun inject(application: MeterApplication)

    fun inject(splashFragment: SplashFragment)

    fun inject(loginFragment: LoginFragment)

    fun inject(registerFragment: RegisterFragment)

    fun inject(accountListFragment: AccountListFragment)

    fun inject(companyListFragment: CompanyListFragment)

    fun inject(accountInfoFragment: AccountInfoFragment)

    fun inject(deviceListFragment: DeviceListFragment)

    fun inject(queryListFragment: QueryListFragment)
}