package dev.akat.smartmeter.local

import android.content.SharedPreferences
import dev.akat.smartmeter.utils.KEY_AUTH_ID
import dev.akat.smartmeter.utils.KEY_LOGGED_IN
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class LocalDataSourceImpl @Inject constructor(private val sharedPreferences: SharedPreferences) :
    LocalDataSource {

    override fun isLoggedIn(): Boolean =
        sharedPreferences.getBoolean(KEY_LOGGED_IN, false)

    override fun setLoggedIn(isLoggedIn: Boolean) {
        sharedPreferences
            .edit()
            .putBoolean(KEY_LOGGED_IN, isLoggedIn)
            .apply()
    }

    override fun getAuthId(): Long =
        sharedPreferences.getLong(KEY_AUTH_ID, -1)

    override fun setAuthId(authId: Long) {
        sharedPreferences
            .edit()
            .putLong(KEY_AUTH_ID, authId)
            .apply()
    }
}