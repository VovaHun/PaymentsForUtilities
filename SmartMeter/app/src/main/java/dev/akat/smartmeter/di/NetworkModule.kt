package dev.akat.smartmeter.di

import com.google.gson.Gson
import com.google.gson.GsonBuilder
import dagger.Module
import dagger.Provides
import dev.akat.smartmeter.network.ApiDateDeserializer
import dev.akat.smartmeter.network.ApiService
import dev.akat.smartmeter.utils.API_DATE_FORMAT
import dev.akat.smartmeter.utils.BASE_URL
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.*
import javax.inject.Singleton

@Module
class NetworkModule {

    @Provides
    @Singleton
    fun provideApiService(retrofit: Retrofit): ApiService {
        return retrofit.create(ApiService::class.java)
    }

    @Provides
    @Singleton
    fun provideRetrofit(client: OkHttpClient, gson: Gson): Retrofit {
        return Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }

    @Provides
    @Singleton
    fun provideOkHttp(): OkHttpClient {
        return OkHttpClient.Builder()
            .build()
    }

    @Provides
    @Singleton
    fun provideGson(dateDeserializer: ApiDateDeserializer): Gson {
        return GsonBuilder()
            .registerTypeAdapter(
                Date::class.java,
                dateDeserializer
            )
            .create()
    }

    @Provides
    @Singleton
    fun provideDateDeserializer(): ApiDateDeserializer {
        return ApiDateDeserializer(API_DATE_FORMAT)
    }
}