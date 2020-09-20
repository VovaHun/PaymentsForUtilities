package dev.akat.smartmeter.ui.login

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.utils.Event
import dev.akat.smartmeter.utils.Validations
import kotlinx.coroutines.launch
import javax.inject.Inject

class LoginViewModel @Inject constructor(private val meterRepository: MeterRepository) :
    ViewModel() {

    private val _isFormValid = MutableLiveData<Event<Boolean>>()
    private val _formErrors = MutableLiveData<MutableList<Validations>>()
    private val _isLoggedIn = MutableLiveData<Event<Boolean>>()
    private val _errorMessage = MutableLiveData<Event<String>>()

    val isFormValid: LiveData<Event<Boolean>> get() = _isFormValid
    val formErrors: LiveData<MutableList<Validations>> get() = _formErrors
    val isLoggedIn: LiveData<Event<Boolean>> get() = _isLoggedIn
    val errorMessage: LiveData<Event<String>> get() = _errorMessage

    var username = ""
        set(value) {
            field = value
            validateForm()
        }

    var password = ""
        set(value) {
            field = value
            validateForm()
        }

    init {
        _formErrors.value = ArrayList()
    }

    private fun validateForm() {
        val errors = _formErrors.value!!
        errors.clear()

        if (username.isEmpty()) errors.add(Validations.EMPTY_USERNAME)
        if (password.isEmpty()) errors.add(Validations.EMPTY_PASSWORD)

        _formErrors.value = errors
        _isFormValid.postValue(Event(errors.isNullOrEmpty()))
    }

    fun authRequest() {
        viewModelScope.launch {
            try {
                val response = meterRepository.authRequest(username, password)

                _errorMessage.postValue(Event(response.error))
                _isLoggedIn.postValue(Event(response.ok))

                // remember login data
                meterRepository.setLoggedIn(response.ok)
                response.data?.let { meterRepository.setAuthId(it.id) }
            } catch (e: Exception) {
            }
        }
    }
}