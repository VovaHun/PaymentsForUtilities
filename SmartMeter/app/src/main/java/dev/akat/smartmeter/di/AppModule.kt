package dev.akat.smartmeter.di

import android.content.Context
import android.content.SharedPreferences
import androidx.preference.PreferenceManager
import dagger.Module
import dagger.Provides
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.local.LocalDataSource
import dev.akat.smartmeter.local.LocalDataSourceImpl
import dev.akat.smartmeter.network.ApiService
import dev.akat.smartmeter.network.NetworkDataSource
import dev.akat.smartmeter.network.NetworkDataSourceImpl
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.repository.MeterRepositoryImpl
import javax.inject.Singleton

@Module
class AppModule(private val application: MeterApplication) {

    @Provides
    @Singleton
    fun provideApplicationContext(): Context = application

    @Provides
    @Singleton
    fun provideMeterRepository(
        localDataSource: LocalDataSource,
        networkDataSource: NetworkDataSource
    ): MeterRepository {
        return MeterRepositoryImpl(localDataSource, networkDataSource)
    }

    @Provides
    @Singleton
    fun provideLocalDataSource(sharedPreferences: SharedPreferences): LocalDataSource {
        return LocalDataSourceImpl(sharedPreferences)
    }

    @Provides
    @Singleton
    fun provideNetworkDataSource(apiService: ApiService): NetworkDataSource {
        return NetworkDataSourceImpl(apiService)
    }

    @Provides
    @Singleton
    fun provideSharedPreferences(context: Context): SharedPreferences {
        return PreferenceManager.getDefaultSharedPreferences(context)
    }
}