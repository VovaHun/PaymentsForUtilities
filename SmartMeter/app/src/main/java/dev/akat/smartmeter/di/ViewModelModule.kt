package dev.akat.smartmeter.di

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import dagger.Binds
import dagger.Module
import dagger.multibindings.IntoMap
import dev.akat.smartmeter.ui.accountInfo.AccountInfoViewModel
import dev.akat.smartmeter.ui.accountList.AccountListViewModel
import dev.akat.smartmeter.ui.companyList.CompanyListViewModel
import dev.akat.smartmeter.ui.login.LoginViewModel
import dev.akat.smartmeter.ui.queryList.QueryListViewModel
import dev.akat.smartmeter.ui.register.RegisterViewModel

@Module
abstract class ViewModelModule {

    @Binds
    internal abstract fun bindViewModelFactory(factory: ViewModelFactory): ViewModelProvider.Factory

    @Binds
    @IntoMap
    @ViewModelKey(LoginViewModel::class)
    abstract fun bindsLoginViewModel(loginViewModel: LoginViewModel): ViewModel

    @Binds
    @IntoMap
    @ViewModelKey(RegisterViewModel::class)
    abstract fun bindsRegisterViewModel(registerViewModel: RegisterViewModel): ViewModel

    @Binds
    @IntoMap
    @ViewModelKey(AccountListViewModel::class)
    abstract fun bindsAccountListViewModel(accountListViewModel: AccountListViewModel): ViewModel

    @Binds
    @IntoMap
    @ViewModelKey(CompanyListViewModel::class)
    abstract fun bindsCompanyListViewModel(companyListViewModel: CompanyListViewModel): ViewModel

    @Binds
    @IntoMap
    @ViewModelKey(AccountInfoViewModel::class)
    abstract fun bindsAccountInfoViewModel(accountInfoViewModel: AccountInfoViewModel): ViewModel

    @Binds
    @IntoMap
    @ViewModelKey(QueryListViewModel::class)
    abstract fun bindsQueryListViewModel(queryListViewModel: QueryListViewModel): ViewModel
}